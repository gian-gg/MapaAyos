const allReports = getAllReports(
  "http://localhost/MapaAyos/api/reports?mode=getReports&status=verified"
);

allReports.then((reports) => displayReports(reports));
