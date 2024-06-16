<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Drag and Drop Sortable List</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
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
        background: #595DB8;
      }
      .sortable-list {
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
    </style>
  </head>
  
<body>
  <ul class="sortable-list">
    @foreach ($tasks as $task)
    <li class="item" data-task-id="{{ $task->id }}" draggable="true">
      <div class="details">
        
        <span>{{ $task->name }}</span>
      </div>
      <i class="uil uil-draggabledots"></i>
    </li>
    @endforeach
  </ul>
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
          if(response.status=="error"){
             toastr.error(response.message);
             return
          }
          toastr.success(response.message);
        },
        error: function(xhr, status, error) {
          //console.error('Error updating tasks:', error);
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
  </script>
  
</body>
</html>
