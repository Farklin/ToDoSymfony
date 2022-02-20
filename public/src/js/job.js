// ajax добавление задачи
function addJob() {
  var form = new FormData();
  form.append("name", $("#planeJobName").val());

  var settings = {
    url: "/todo/api/create-job",
    method: "POST",
    timeout: 0,
    processData: false,
    mimeType: "multipart/form-data",
    contentType: false,
    data: form,
  };

  $.ajax(settings).done(function (response) {
    $(".tab-pane.show>.job-container").append($.parseJSON(response).html);
  });
}

// ajax выполнение задачи
function endJob(element) {
  date = new Date();
  var form = new FormData();
  form.append(
    "date_finish",
    date.toLocaleDateString() + " " + date.toLocaleTimeString()
  );

  var settings = {
    url: "/todo/api/end-job/" + element.data("id"),
    method: "POST",
    timeout: 0,
    processData: false,
    mimeType: "multipart/form-data",
    contentType: false,
    data: form,
  };

  $.ajax(settings).done(function (response) {
    // Выставление даты в карточку задачи
    if ($.parseJSON(response).datetime != null) {
      element
        .closest(".job")
        .find(".date-finish")
        .html($.parseJSON(response).datetime);
    } else {
      element.closest(".job").find(".date-finish").html("");
    }
  });
}
// Удаление задачи
function deleteJob(id) {
  $("#container-job-" + id).slideUp("slow", function () {
    $(this).remove();
  });

  var settings = {
    url: "/todo/api/delete-job/" + id,
    method: "DELETE",
    timeout: 0,
    processData: false,
    mimeType: "multipart/form-data",
    contentType: false,
  };

  $.ajax(settings);
}

//Выполнение развыполнение задачи
function changeJob(id) {
  var checkbox = $("#job" + id);
  if (!checkbox.parent().hasClass("done")) {
    checkbox.parent().addClass("done");
    endJob(checkbox);

    $("#nav-activ-job>.job-container>.job.done").remove();
  } else {
    checkbox.parent().removeClass("done");
    endJob(checkbox);
    $("#nav-done-job>.job-container>.job:not(.done)").remove();
  }
}

//  создание задачи событие
$("#btn-create-job").click(function () {
  addJob();
  $("#planeJobName").val("");
});

// Фильтрация задач по статусу
function filterJob(status, container) {
  var settings = {
    url: "/todo/api/filter-job/",
    method: "GET",
    data: { status: status },
  };

  $.ajax(settings).done(function (response) {
    container.html(response.html);
  });
}

// Вкладки задач
$("#nav-activ-job-tab").click(function () {
  $(".job-container").html("");
  filterJob(1, $("#nav-activ-job>.job-container"));
});

$("#nav-all-job-tab").click(function () {
  $(".job-container").html("");
  filterJob("", $("#nav-all-job>.job-container"));
});

$("#nav-done-job-tab").click(function () {
  $(".job-container").html("");
  filterJob(0, $("#nav-done-job>.job-container"));
});
