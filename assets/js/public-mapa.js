const allReports = getAllReports(
  "http://localhost/MapaAyos/api/reports.php?mode=getAll"
);

allReports.then((reports) => displayReports(reports, "complete"));
