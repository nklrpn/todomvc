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
			this.renderFooter();
		},
		renderFooter: function() {
			var todosCount = Control.getActiveTodos().length;
			$('.todo-count-val').text(todosCount);

			var todosCompleted = Control.getCompletedTodos().length;
			$(".clear-completed").addClass("hidden");
			if (todosCompleted) {
				$(".clear-completed").removeClass("hidden");
			}
		},
	};

	var Control = {
		add: function(e) {
			var val = $(e.target).val().trim();

			if (e.which !== 13 || !val) {
				return;
			}

			var url = '/add/text/' + val;
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
			var completedTodos = this.getCompletedTodos();
			completedTodos.remove();
		},
		toggle: function(e) {
			var item = $(e.target).closest("li");
			
			var url = '/toggle_state/id/' + item.data('id');
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
			var todos = this.getData();

			todos.each(function(todo) {
				if (isChecked) {
					$(this).addClass("completed");
					$(this).find(".toggle").prop("checked", true);
				} else {
					$(this).removeClass("completed");
					$(this).find(".toggle").prop("checked", false);
				}
			});
			App.render();
		},
		edit: function(e) {
			var item = $(e.target).closest("li");
			item.addClass("editing").find(".edit");
		},
		editKeyup: function(e) {
			var item = $(e.target).closest("li");

			if (e.which === 13) {
				var url = '/edit/id/' + item.data('id') + '/text/' + $(e.target).val();
				$.ajax({
					type: 'GET',
					url: url,
					success: function(data) {
						App.render();
					}
				});
			}
		},
		getActiveTodos: function() {
			var todos = this.getData();
			return todos.filter(":not('.completed')");
		},
		getCompletedTodos: function() {
			var todos = this.getData();
			return todos.filter(".completed");
		},
		destroy: function(e) {
			var item = $(e.target).closest("li");

			var url = '/destroy/id/' + item.data('id');
			$.ajax({
				type: 'GET',
				url: url,
				success: function(data) {
					App.render();
				}
			});
		},
		getData: function() {
			return $(".todo-list li");
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
			$.ajax({
				type: 'GET',
				url: '/show',
				success: function(data) {
					$('.todo-list').html(data);
					var todos = $(".todo-list li");
				}
			});
		},
		showFiltered: function(e) {
			var el = e ? $(e.target) : '';
			var filter = (el && el.data('filter')) ? el.data('filter') : this.getFilter();
			var todos = Control.getData();

			switch (filter) {
				case 'active':
					$(todos).hide();
					$(Control.getActiveTodos()).show();
					break;
				case 'completed':
					$(todos).hide();
					$(Control.getCompletedTodos()).show();
					break;
				default:
					$(todos).show();
			}
		},
	};

	App.init();
});
