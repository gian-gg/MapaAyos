import { fetchAPI } from "./utils/api-utils.js";
import { displayReports } from "./utils/mapa-utils.js";

fetchAPI(
  "http://localhost/MapaAyos/api/reports?mode=getReports&status=verified"
).then((data) => displayReports(data.reports));
