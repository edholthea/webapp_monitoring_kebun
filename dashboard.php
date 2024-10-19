<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

require 'inc/config.php';

// Fetch activities for logged-in user with optional date filter
$user_id = $_SESSION['user_id'];
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Prepare the base SQL query
$sql = "SELECT * FROM kegiatan WHERE user_id = :user_id";

// Add date filtering conditions if start_date and/or end_date are provided
if (!empty($start_date) && !empty($end_date)) {
    $sql .= " AND DATE(tanggal) BETWEEN :start_date AND :end_date";
} elseif (!empty($start_date)) {
    $sql .= " AND DATE(tanggal) >= :start_date";
} elseif (!empty($end_date)) {
    $sql .= " AND DATE(tanggal) <= :end_date";
}

$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id);

if (!empty($start_date)) {
    $stmt->bindParam(':start_date', $start_date);
}
if (!empty($end_date)) {
    $stmt->bindParam(':end_date', $end_date);
}

$stmt->execute();
$kegiatan = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../assets/img/kebunlogo_kecil.png">
    <title>Aplikasi Monitoring Kegiatan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
</head>
<body>
    <?php include 'inc/header.php'; ?>
    <div class="container mt-3">
    <div>
    <center><img src="../assets/img/kebunlogo_kecil.png" alt="logo kebun data"></center>
    </div>
        <center><h3>Aplikasi Monitoring Kegiatan | by Kebun Data</h3></center><hr>
         
      	<!-- Form Filter Tanggal -->
      <h6><stromg>Cari Tanggal Kegiatan</strong></h6>
        <form method="GET" action="" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Tanggal Mulai:</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" value="<?= isset($_GET['start_date']) ? $_GET['start_date'] : '' ?>">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">Tanggal Akhir:</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" value="<?= isset($_GET['end_date']) ? $_GET['end_date'] : '' ?>">
                </div>
                <div class="col-md-3 d-flex align-items-end">
            		<button type="submit" class="btn btn-primary btn-block"><i class="fas fa-filter"></i> Filter</button>
        		</div>
        		<div class="col-md-3 d-flex align-items-end">
            		<a href="dashboard.php" class="btn btn-secondary btn-block"><i class="fas fa-sync-alt"></i> Reset</a>
        		</div>
            </div>
        </form>
      
      	<!-- Tombol Tambah Kegiatan -->
         <center><a href="tambah_kegiatan.php" class="fw-bold btn btn-success mb-3 mt-3"><i class="fas fa-plus"></i> Tambah Kegiatan</a></center><br>
        
      
      	<table table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                  	<th>No</th>
                    <th>Tanggal</th>
                    <th>Nama Petugas</th>
                    <th>Kegiatan</th>
                    <th>Keterangan</th>
                    <th>Waktu Input</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
              	<?php $no = 1; ?>
                <?php foreach ($kegiatan as $item): ?>
                    <tr>
                      	<td><?= $no++; ?></td>
                        <td><?= date('d-m-Y', strtotime($item['tanggal'])); ?></td>
                        <td><?= $item['nama_petugas']; ?></td>
                        <td><?= $item['kegiatan']; ?></td>
                        <td><?= $item['keterangan']; ?></td>
                      	<td><?= date('d-m-Y H:i:s', strtotime($item['created_at'])); ?></td>
                        <td>
                            <a href="edit_kegiatan.php?id=<?= $item['id']; ?>" class="btn btn-success btn-sm"><i class="fas fa-edit" style="color: #ffffff;"></i> Edit</a>
                            <a href="delete_kegiatan.php?id=<?= $item['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus kegiatan ini?')"><i class="fas fa-trash-alt"></i> Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!-- Tombol Hapus Semua Kegiatan -->
        <a href="delete_all_kegiatan.php" class="fw-bold btn btn-danger mb-3 mt-4" onclick="return confirm('Yakin ingin menghapus semua kegiatan? Ini tidak bisa dikembalikan!')"><i class="fas fa-trash-alt"></i> Hapus Semua Data</a>
    </div>
    <?php include 'inc/footer.php'; ?>
</body>
</html>

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../../plugins/jszip/jszip.min.js"></script>
<script src="../../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- Page specific script -->
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": true,
      "buttons": ["copy", "csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "responsive": true,
    });
  });
</script>