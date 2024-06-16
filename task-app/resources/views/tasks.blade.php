<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Drag and Drop Sortable List</title>

  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <style>
    /* Import Google font - Poppins */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }
    body {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      background: #e7ebff;
    }
    .container {
      width: 425px;
      padding: 25px;
      background: #fff;
      border-radius: 7px;
      padding: 30px 25px 20px;
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }
    .sortable-list .item {
      list-style: none;
      display: flex;
      cursor: move;
      background: #fff;
      align-items: center;
      border-radius: 5px;
      padding: 10px 13px;
      margin-bottom: 11px;
      border: 1px solid #ccc;
      justify-content: space-between;
    }
    .item .details {
      display: flex;
      align-items: center;
    }
    .item .details img {
      height: 43px;
      width: 43px;
      pointer-events: none;
      margin-right: 12px;
      object-fit: cover;
      border-radius: 50%;
    }
    .item .details span {
      font-size: 1.13rem;
    }
    .item i {
      color: #474747;
      font-size: 1.13rem;
    }
    .item.dragging {
      opacity: 0.6;
    }
    .item.dragging :where(.details, i) {
      opacity: 0;
    }
    .form-container {
      margin-bottom: 20px;
    }
    .form-container input[type="text"] {
      width: calc(100% - 22px);
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      margin-bottom: 10px;
    }
    .form-container button {
      width: 100%;
      padding: 10px;
      background: #595DB8;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .edit-task-button{

      background-color: #00b919;
    }
    .delete-task-button{
      background-color: #ff3e3e;
    }
       .edit-task-button,
    .delete-task-button {
      color: white;
      border: none;
      padding: 8px 12px;
      margin-left: 5px;
      cursor: pointer;
      border-radius: 5px;
    }
    .edit-task-button:hover{
      background-color: #00b919b5;
    }
    .delete-task-button:hover {
      background-color: #ff5050de;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="form-container">
      <input type="text" id="new-task-name" style="width: 100%" placeholder="Enter task name">
      <button id="add-task-button">Add Task</button>
    </div>
    <ul class="sortable-list">
      @foreach ($tasks as $task)
      <li class="item" data-task-id="{{ $task->id }}" draggable="true">
        <div class="details">
          <span>{{ $task->name }}</span>
        </div>
        <i class="uil uil-draggabledotsd">
        <div>
          <button class="edit-task-button btn btn-success" data-task-id="{{ $task->id }}" data-toggle="modal" data-target="#modal-edit-{{$task->id}}">Edit</button>
          <button class="delete-task-button btn btn-danger" value="{{ $task->id }}" onclick="del(this)">Delete</button>
        </div>
        </i>
      </li>
      @endforeach
    </ul>
  </div>

         <!-- Edit Task Modal -->
            @foreach ($tasks as $item)
            <div id="modal-edit-{{$item['id']}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form class="editForm" method="post">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="type" value="0">
                                <input type="hidden" name="editformId" id="editformId" value="{{$item['id']}}">
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Task</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <div class="form-group">
                            <label for="edit-task-name" class="col-form-label">Task Name:</label>
                            <input type="text" class="form-control" value="{{$item->name}}" name="name" id="edit-task-name">
                          </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn rounded-pill p-1" id="editbtn{{$item['id']}}" style="width: 100%; background-color: #08228a9f;color: white" type="submit">
                                    Submit
                            </button>
                            <button class="btn rounded-pill p-1" id="editloader{{$item['id']}}" style="width: 100%; background-color: #08228a9f;color: white;display:none;" type="button">
                                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                    Saving Data...
                            </button>
                        </div>
                        </form>
                    </div>
                </div>
            </div><!-- /.modal -->
        @endforeach
        


          <!-- jQuery, Popper.js, and Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



  <script>
    const sortableList = document.querySelector(".sortable-list");
    const items = sortableList.querySelectorAll(".item");

    items.forEach(item => {
      item.addEventListener("dragstart", () => {
        // Adding dragging class to item after a delay
        setTimeout(() => item.classList.add("dragging"), 0);
      });

      // Removing dragging class from item on dragend event
      item.addEventListener("dragend", () => {
        item.classList.remove("dragging");
        logAffectedTasks();
      });
    });

    function logAffectedTasks() {
      // Select all items and map them to an array of task objects
      const csrfToken = $('meta[name="csrf-token"]').attr('content'); // Get CSRF token from meta tag
      const items = sortableList.querySelectorAll('.item');
      const affectedTasks = Array.from(items).map(item => {
        const taskId = item.getAttribute('data-task-id');
        const taskName = item.querySelector('.details span').textContent;
        return { id: taskId, name: taskName };
      });

      // Send an AJAX POST request with the affectedTasks data
      $.ajax({
        url: '/sort-tasks', // Replace with your actual endpoint
        type: 'POST',
        headers: {
        'X-CSRF-TOKEN': csrfToken
        },
        contentType: 'application/json',
        data: JSON.stringify(affectedTasks),    

        success: function(response) {
          //console.log('Tasks updated successfully:', response);
          toastr.success('Tasks and Priority updated successfully!');
        },
        error: function(xhr, status, error) {
          console.error('Error updating tasks:', error);
          toastr.error('Error updating tasks. Please try again.');
        }
      });
    }

    const initSortableList = (e) => {
      e.preventDefault();
      const draggingItem = document.querySelector(".dragging");
      // Getting all items except currently dragging and making array of them
      let siblings = [...sortableList.querySelectorAll(".item:not(.dragging)")];

      // Finding the sibling after which the dragging item should be placed
      let nextSibling = siblings.find(sibling => {
        return e.clientY <= sibling.offsetTop + sibling.offsetHeight / 2;
      });

      // Inserting the dragging item before the found sibling
      sortableList.insertBefore(draggingItem, nextSibling);
    }

    sortableList.addEventListener("dragover", initSortableList);
    sortableList.addEventListener("dragenter", e => e.preventDefault());

    // Add event listener for the "Add Task" button
    document.getElementById('add-task-button').addEventListener('click', function() {
      const taskName = document.getElementById('new-task-name').value;
      if (taskName.trim() === '') {
        toastr.error('Please enter a task name.');
        return;
      }
      const btn=document.getElementById('add-task-button');
      btn.disabled=true;
      
      const csrfToken = $('meta[name="csrf-token"]').attr('content');

      $.ajax({
        url: '/tasks', // Replace with your actual endpoint
        type: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken
        },
        data: { name: taskName },
        success: function(response) {
          if (response.success) {
            // Create new task element
            const newTask = document.createElement('li');
            newTask.classList.add('item');
            newTask.setAttribute('data-task-id', response.task.id);
            newTask.setAttribute('draggable', 'true');

            const detailsDiv = document.createElement('div');
            detailsDiv.classList.add('details');

            const span = document.createElement('span');
            span.textContent = response.task.name;
            detailsDiv.appendChild(span);

            const icon = document.createElement('i');
            icon.classList.add('uil', 'uil-draggabledots');

            newTask.appendChild(detailsDiv);
            newTask.appendChild(icon);

            sortableList.appendChild(newTask);

            // Clear the input field
            document.getElementById('new-task-name').value = '';

            // Re-attach event listeners
            newTask.addEventListener('dragstart', () => {
              setTimeout(() => newTask.classList.add('dragging'), 0);
            });

            newTask.addEventListener('dragend', () => {
              newTask.classList.remove('dragging');
              logAffectedTasks();
            });

            toastr.success('Task added successfully!');
            location.reload()
          } else {
            toastr.success('Task added successfully!');
            setTimeout(()=>{
              location.reload()
            },1000)
          }
        },
        error: function(xhr, status, error) {
          console.error('Error adding task:', error);
          toastr.error('Error adding task. Please try again.');
        }
      });
    });

        //Deleting Tasks
        function del(e){
        let id=e.value;
        Swal.fire({
            title: "Confirm deletion",
            text: "You won't be able to revert this!",
            type: "error",
            showCancelButton: !0,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((t)=>{
        if(t.value){
                $.ajax({
                    type: "DELETE",
                    url: "tasks/"+id,
                    data:{
                        _token:"{{csrf_token()}}", id
                    },
                    success: function (response) { console.log(response)

                        Swal.fire("Deleted", "Successfully.", "success").then(()=>{
                        location.href='/tasks'})
                    },
                    error: function(res){console.log(res)
                        Swal.fire("Error!", "Try again later...", "error");
                    }
                });
            }
            })
        }


// Edit settings Form
$(".editForm").on('submit', function(e) {
  e.preventDefault();

  const form = $(this);
  var itemId = form.find('input[name="editformId"]').val();
  var btn = $("#editbtn" + itemId);
  var loader = $("#editloader" + itemId);
  btn.hide();
  loader.show();
  let data = form.serialize();
$.ajax({
    type: 'PATCH',
    url: '/tasks/'+itemId,
    data: data,
    success: function (response) { console.log(response)
        toastr.success('Task Updated successfully!');
        location.href='/tasks'
    },
    error: function(res){ console.log(res)
        btn.show();
        loader.hide();
        toastr.success('Task Updated successfully!');
        location.href='/tasks'
    }
});
})

  </script>
</body>
</html>
