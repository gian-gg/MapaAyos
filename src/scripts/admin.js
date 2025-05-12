import { fetchAPI } from "./utils/api-utils.js";

function displayUser(userID) {
  fetchAPI(
    "http://localhost/MapaAyos/api/user?mode=getUserByID&userID=" + userID
  ).then((data) => {
    const user = data.data;
    if (!user) return;

    document.querySelectorAll(".user-row").forEach((row) => {
      row.classList.remove("active");
    });
    document.getElementById("user-" + userID).classList.add("active");

    let userElement = `
      <img src="/MapaAyos/public/uploads/pfp/${
        user["hasProfilePic"] ? userID : "default"
      }.png" alt="Profile Image" />
      <h3>${user["firstName"]} ${user["lastName"]}</h3>
      <p>Email: ${user["email"]}</p>

      <form method="POST">
        <input type="hidden" name="userID" value="${userID}" />
        <label for="update-role">Update Role:</label>
        <select id="update-role" name="update-role" required>
          <option value="" disabled selected>Select</option>
          ${
            user["role"] === "user"
              ? `<option value="official">Official</option><option value="admin">Admin</option>`
              : user["role"] === "official"
              ? `<option value="user">User</option><option value="admin">Admin</option>`
              : `<option value="user">User</option><option value="official">Official</option>`
          }
        </select>

        <div id="barangay-select-container"></div>

        <button type="submit">Update Status</button>
      </form>
    `;

    document.getElementById("user-information").innerHTML = userElement;

    // Attach change event listener after rendering
    const updateRoleSelect = document.getElementById("update-role");
    const barangayContainer = document.getElementById(
      "barangay-select-container"
    );

    updateRoleSelect.addEventListener("change", () => {
      if (updateRoleSelect.value === "official") {
        fetchAPI(
          "http://localhost/MapaAyos/api/baranggay?mode=getAllBaranggays"
        )
          .then((response) => {
            const barangays = response.data;
            if (barangays && barangays.length > 0) {
              barangayContainer.innerHTML = `
          <label for="baranggay">Assign Barangay:</label>
          <select id="baranggay" name="baranggay" required>
            <option value="" disabled selected>Select</option>
            ${barangays
              .map(
                (barangay) =>
                  `<option value="${barangay.id}">${barangay.name}</option>`
              )
              .join("")}
          </select>
              `;
            } else {
              barangayContainer.innerHTML = `<p>No barangays available.</p>`;
            }
          })
          .catch((error) => {
            console.error("Error fetching barangays:", error);
            barangayContainer.innerHTML = `<p>Error loading barangays.</p>`;
          });
      } else {
        barangayContainer.innerHTML = ""; // Clear it if another role is selected
      }
    });
  });
}

window.displayUser = displayUser;
