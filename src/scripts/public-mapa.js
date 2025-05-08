const allReports = getAllReports(
  "http://localhost/MapaAyos/api/reports?mode=getAll"
);

allReports.then((reports) => displayReports(reports, "complete"));
