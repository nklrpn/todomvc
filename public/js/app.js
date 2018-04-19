$(function() {
	var todos = $(".todo-list li");

	var App = {
		init: function() {
			this.bindEvents();
			this.render();
		},
		bindEvents: function() {
			$("#new-todo").on("keyup", Control.add.bind(this));
			$("#toggle-all").on("click", Control.toggleAll.bind(this));
			$(".todo-list").on("click", ".toggle", Control.toggle.bind(this));
			$(".todo-list").on("click", ".destroy", Control.destroy.bind(this));
			$(".footer").on("click", ".clear-completed", Control.clear.bind(this));
			$(".footer").on("change", "#toggle-all", Control.toggleAll.bind(this));
			$(".footer").on("click", ".filters", this.render.bind(this));
		},
		render: function(e) {
			View.show(e);
			this.renderFooter();
			$("#new-todo").focus();
		},
		renderFooter: function() {
			var todosCount = Control.getActiveTodos().length;
			$('.todo-count-val').text(todosCount);
		},
	};

	var Control = {
		add: function(e) {
			var val = $(e.target).val().trim();

			if (e.which !== 13 || !val) {
				return;
			}

			alert(val);
		},
		clear: function() {
			var completedTodos = todos.filter(".completed");
			completedTodos.remove();
		},
		toggleAll: function(e) {
			var isChecked = $(e.target).prop("checked");
			todos.each(function(todo) {
				if (isChecked) {
					$(this).addClass("completed");
					$(this).find(".toggle").prop("checked", true);
				} else {
					$(this).removeClass("completed");
					$(this).find(".toggle").prop("checked", false);
				}
			});
			App.renderFooter();
		},
		toggle: function(e) {
			$(e.target).closest("li").toggleClass("completed");
			App.renderFooter();
		},
		getActiveTodos: function() {
			return todos.filter(":not('.completed')");
		},
		getCompletedTodos: function() {
			return todos.filter(".completed");
		},
		destroy: function(e) {
			var item = $(e.target).closest("li");
			item.addClass("completed");
			item.remove();
			App.renderFooter();
		},
	};

	var View = {
		getFilter: function() {
			if (document.URL.indexOf('#') == -1) {
				return "";
			}
			return document.URL.substr(document.URL.indexOf('#') + 2); 
		},
		show: function(e) {
			var el = e ? $(e.target) : '';
			var filter = (el && el.data('filter')) ? el.data('filter') : this.getFilter();

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
