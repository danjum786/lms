function myAlert(type, msg, position = "body") {
  let element = document.createElement("div");

  element.innerHTML = `
    <div class="alert alert-${type} alert-dismissible fade show custom-alert" role="alert">
      ${msg}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    `;
  if (position == "body") {
    document.body.append(element);
    element.classList.add("custom-alert");
  } else {
    document.getElementById(position).append(element);
  }
  setTimeout(rem_myAlert, 3000);
}

function rem_myAlert() {
  document.getElementsByClassName("alert")[0].remove();
}


// function alert($type, $msg)
// {
//   echo <<<alert
//   <div class="alert alert-$type alert-dismissible fade show custom-alert" role="alert">
//     $msg
//     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
//   </div>
//   alert;
// }