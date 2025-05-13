import { fetchAPI } from "./utils/api-utils.js";

function displayUser(userID) {
  const userInfoElement = document.getElementById("user-information");

  // Add loading state
  userInfoElement.style.display = "block";
  userInfoElement.innerHTML = `
    <div class="text-center">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
  `;

  fetchAPI(
    "https://mapaayos.dcism.org/api/user?mode=getUserByID&userID=" + userID
  ).then((data) => {
    const user = data.data;
    if (!user) {
      userInfoElement.innerHTML = `
        <div class="text-center text-danger">
          <i class="bi bi-exclamation-circle"></i>
          <p>User not found</p>
        </div>
      `;
      return;
    }

    document.querySelectorAll(".user-row").forEach((row) => {
      row.classList.remove("active");
    });
    document.getElementById("user-" + userID).classList.add("active");

    let userElement = `
      <div class="user-info-header">
        <img src="/public/uploads/pfp/${
          user["hasProfilePic"] ? userID : "default"
        }.png" alt="Profile Image" />
        <div class="user-info-title">
          <h3>${user["firstName"]} ${user["lastName"]}</h3>
          <p><i class="bi bi-envelope"></i> ${user["email"]}</p>
          <span class="role-badge ${user["role"].toLowerCase()}">${
      user["role"]
    }</span>
        </div>
      </div>

      <form method="POST" class="mt-4">
        <input type="hidden" name="userID" value="${userID}" />
        <div class="form-group">
          <label for="update-role">
            <i class="bi bi-person-gear"></i> Update Role
          </label>
          <select id="update-role" name="update-role" required>
            <option value="" disabled selected>Select new role</option>
            ${
              user["role"] === "user"
                ? `<option value="official">Official</option><option value="admin">Admin</option>`
                : user["role"] === "official"
                ? `<option value="user">User</option><option value="admin">Admin</option>`
                : `<option value="user">User</option><option value="official">Official</option>`
            }
          </select>
        </div>

        <div id="barangay-select-container"></div>

        <button type="submit">
          <i class="bi bi-check-circle"></i> Update Status
        </button>
      </form>
    `;

    userInfoElement.innerHTML = userElement;
    userInfoElement.classList.add("visible");

    // Attach change event listener after rendering
    const updateRoleSelect = document.getElementById("update-role");
    const barangayContainer = document.getElementById(
      "barangay-select-container"
    );

    updateRoleSelect.addEventListener("change", () => {
      if (updateRoleSelect.value === "official") {
        barangayContainer.innerHTML = `
          <div class="text-center my-3">
            <div class="spinner-border spinner-border-sm text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
          </div>
        `;

        fetchAPI(
          "https://mapaayos.dcism.org/api/baranggay?mode=getAllBaranggays"
        )
          .then((response) => {
            const barangays = response.data;
            if (barangays && barangays.length > 0) {
              barangayContainer.innerHTML = `
                <div class="form-group">
                  <label for="baranggay">
                    <i class="bi bi-geo-alt"></i> Assign Barangay
                  </label>
                  <select id="baranggay" name="baranggay" required>
                    <option value="" disabled selected>Select barangay</option>
                    ${barangays
                      .map(
                        (barangay) =>
                          `<option value="${barangay.id}">${barangay.name}</option>`
                      )
                      .join("")}
                  </select>
                </div>
              `;
            } else {
              barangayContainer.innerHTML = `
                <p class="text-warning">
                  <i class="bi bi-exclamation-triangle"></i>
                  No barangays available.
                </p>
              `;
            }
          })
          .catch((error) => {
            console.error("Error fetching barangays:", error);
            barangayContainer.innerHTML = `
              <p class="text-danger">
                <i class="bi bi-exclamation-circle"></i>
                Error loading barangays.
              </p>
            `;
          });
      } else {
        barangayContainer.innerHTML = ""; // Clear it if another role is selected
      }
    });
  });
}

window.displayUser = displayUser;
