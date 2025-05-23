async function fetchAPI(API_URL) {
  try {
    const response = await fetch(API_URL, {
      method: "GET",
      headers: {
        Authorization: "Bearer mapaayos123", // Replace with your actual token
      },
    });

    if (!response.ok) {
      throw new Error("Network response was not ok");
    }

    const data = await response.json();
    return data;
  } catch (error) {
    console.error("Error fetching reports:", error);
    return [];
  }
}

// // Function to get address from coordinates using Nominatim API, there might be a better approach than this pero this will do for now :3
function getAddressFromCoords(lat, lng) {
  const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`;

  return fetch(url, {
    headers: {
      "User-Agent": "MapaAyos/1.0", // Replace with your app name (required by Nominatim)
    },
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      if (data && data.address) {
        return {
          address: data.address,
          road: data.address.road || null,
          suburb: data.address.suburb || null,
          city:
            data.address.city ||
            data.address.town ||
            data.address.village ||
            null,
          display_name: data.display_name || null,
        };
      } else {
        return { error: "No address found" };
      }
    })
    .catch((error) => {
      return { error: error.message };
    });
}

export { fetchAPI, getAddressFromCoords };
