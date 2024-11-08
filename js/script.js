// Toggle dropdown menu
document.querySelectorAll(".dropdown > a").forEach((item) => {
  item.addEventListener("click", (event) => {
    event.preventDefault();
    const parent = item.parentElement;
    parent.classList.toggle("open");
  });
});

// Optional: Toggle sidebar on smaller screens
document.addEventListener("DOMContentLoaded", () => {
  const sidebar = document.querySelector(".sidebar");
  const toggleSidebar = () => {
    sidebar.classList.toggle("open");
  };

  // Add sidebar toggle button functionality if needed
  // Sidebar toggle functionality
  document.getElementById("sidebar-toggle").addEventListener("click", () => {
    document.querySelector(".sidebar").classList.toggle("open");
  });
});

function showJsAlert(color, msg, timeout = 3000) {
  // Create alert container
  const alertBox = document.createElement("div");
  alertBox.textContent = msg;
  alertBox.style.minWidth = "200px";
  alertBox.style.borderRadius = "5px";
  alertBox.style.textAlign = "right";
  alertBox.style.padding = "20px";
  alertBox.style.position = "absolute";
  alertBox.style.top = "20px";
  alertBox.style.right = "20px";
  alertBox.style.color = "white";
  alertBox.style.backgroundColor = color;
  alertBox.style.transition = "opacity 0.5s";
  alertBox.style.zIndex = "1000";

  // Add alert to the document body
  document.body.appendChild(alertBox);

  // Automatically dismiss the alert after the specified timeout
  setTimeout(() => {
    alertBox.style.opacity = "0"; // Fade out
    setTimeout(() => {
      alertBox.remove(); // Remove from DOM after fade out
    }, 500); // Matches the transition duration
  }, timeout);
}

// Delete User Code
function deleteUser(userId) {
  if (confirm("Are you sure you want to delete this user?")) {
    fetch("delete_user.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `user_id=${userId}`,
    })
      .then((response) => response.text())
      .then((result) => {
        if (result === "success") {
          showJsAlert("#155724", "User deleted successfully");
          setTimeout(() => {
            window.location.reload(); // Reload to reflect changes
          }, 1000);
        } else {
          showJsAlert(
            "#721c24",
            "Something went wrong while deleting the user"
          );
        }
      })
      .catch((error) => console.error("Error:", error));
  }
}
