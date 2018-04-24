$(function() {
	var App = {
		init: function() {
			this.bindEvents();
			this.render();
		},
		bindEvents: function() {
			$("#new-todo").on("keyup", Control.add.bind(this));
			$("#toggle-all").on("click", Control.toggleAll.bind(this));
			$(".todo-list")
				.on("click", ".toggle", Control.toggle.bind(this))
				.on("click", ".destroy", Control.destroy.bind(this))
				.on("dblclick", "label", Control.edit.bind(this))
				.on("keyup", ".edit", Control.editKeyup.bind(this));
			$(".footer")
				.on("click", ".clear-completed", Control.clear.bind(this))
				.on("change", "#toggle-all", Control.toggleAll.bind(this))
				.on("click", ".filters", View.showFiltered.bind(this));
		},
		render: function(e) {
			View.show();
			App.renderFooter();
		},
		renderFooter: function() {
			View.showCounter();
			View.clearCompletedLink();
		},
	};

	var Data = {
		getData: function() {
			var todos = $(".todo-list li");
			return todos;
		},
		getActiveTodos: function() {
			return this.getData().filter(":not('.completed')");
		},
		getCompletedTodos: function() {
			return this.getData().filter(".completed");
		},
		getCount: function() {
			var count = Data.getActiveTodos().length;
			return count;
		}
	};

	var Control = {
		add: function(e) {
			var val = $(e.target).val().trim();

			if (e.which !== 13 || !val) {
				return;
			}

			var url = '/add/text/' + val;

			if (!val) {
				return;
			}
			
			$.ajax({
				type: 'GET',
				url: url,
				success: function(data) {
					$(e.target).val('');
					App.render();

				}
			});
		},
		clear: function() {
			var completedTodos = Data.getCompletedTodos();

			completedTodos.each(function(index) {
				var id = $(this).data('id');
				var url = '/destroy/id/' + id;

				if (!id) {
					return;
				}

				$.ajax({
					type: 'GET',
					url: url,
					success: function(data) {
						$(id).remove();
						App.render();
					}
				});
			});
		},
		toggle: function(e) {
			var item = $(e.target).closest("li");
			var id = item.data('id');
			var url = '/toggle_state/id/' + id;
			
			if (!id) {
				return true;
			}

			$.ajax({
				type: 'GET',
				url: url,
				success: function(data) {
					App.render();
				}
			});
		},
		toggleAll: function(e) {
			var isChecked = $(e.target).prop("checked");
			var todos = Data.getData();

			todos.each(function(index) {
				if (isChecked) {
					var isSkipped = $(this).hasClass("completed");
					$(this).addClass("completed");
					$(this).find(".toggle").prop("checked", true);
				} else {
					var isSkipped = !$(this).hasClass("completed");
					$(this).removeClass("completed");
					$(this).find(".toggle").prop("checked", false);
				}
				
				var id = $(this).data('id');
				var url = '/toggle_state/id/' + id;
				
				if (!id || isSkipped) {
					return true;
				}

				$.ajax({
					type: 'GET',
					url: url,
					success: function(data) {
						App.render();
					}
				});
			});
		},
		edit: function(e) {
			var item = $(e.target).closest("li");
			item.addClass("editing").find(".edit");
		},
		editKeyup: function(e) {
			var item = $(e.target).closest("li");

			if (e.which === 13) {
				var id = item.data('id');
				var text = $(e.target).val();
				var url = '/edit/id/' + id + '/text/' + text;

				if (!id || !text) {
					return true;
				}

				$.ajax({
					type: 'GET',
					url: url,
					success: function(data) {
						App.render();
					}
				});
			}
		},
		destroy: function(e) {
			var item = $(e.target).closest("li");
			var id = item.data('id');
			var url = '/destroy/id/' + id;

			if (!id) {
				return true;
			}

			$.ajax({
				type: 'GET',
				url: url,
				success: function(data) {
					App.render();
				}
			});
		},
	};

	var View = {
		getFilter: function() {
			if (document.URL.indexOf('#') == -1) {
				return "";
			}
			return document.URL.substr(document.URL.indexOf('#') + 2); 
		},
		show: function() {
			if (this.getFilter()) {
				this.showFiltered();
				return true;
			}

			$.ajax({
				type: 'GET',
				url: '/show',
				success: function(data) {
					$('.todo-list').html(data);
					App.renderFooter();
				}
			});
		},
		showFiltered: function(e) {
			var el = e ? $(e.target) : '';
			var filter = (el && el.data('filter')) ? el.data('filter') : this.getFilter();
			var todos = Data.getData();
			
			switch (filter) {
				case 'active':
					$(todos).show();
					$(Data.getCompletedTodos()).hide();
					break;
				case 'completed':
					$(todos).show();
					$(Data.getActiveTodos()).hide();
					break;
				default:
					$(todos).show();
			}
		},
		showCounter: function() {
			$('.todo-count-val').text(Data.getCount());
		},
		clearCompletedLink: function() {
			var todosCompleted = Data.getCompletedTodos().length;

			$(".clear-completed").addClass("hidden");
			if (todosCompleted) {
				$(".clear-completed").removeClass("hidden");
			}
		},
	};

	App.init();
});
