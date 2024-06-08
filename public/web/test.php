<!DOCTYPE html>
<html>

<head>
    <title>Cara Menggunakan Datatables | Malas Ngoding</title>

    <script src="../adminlte/plugins/jquery/jquery.min.js"></script>

    <script src="../adminlte/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="js/datatables.min.js "></script>
    <link rel="stylesheet" type="text/css" href="css/datatables.min.css">
</head>

<body>
    <center>
        <h1>Menampilkan data dengan datatables | Malas Ngoding</h1>
    </center>
    <br />
    <br />
    <div class="container">
        <table class="table table-striped table-bordered data">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Pekerjaan</th>
                    <th>Usia</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Andi1</td>
                    <td>Jakarta</td>
                    <td>Web Designer</td>
                    <td>21</td>
                    <td>Aktif</td>
                </tr>
                <tr>
                    <td>Malas Ngoding1</td>
                    <td>Bandung</td>
                    <td>Web Developer</td>
                    <td>26</td>
                    <td>Aktif</td>
                </tr>
                <tr>
                    <td>Malas Ngoding2</td>
                    <td>Bandung</td>
                    <td>Web Developer</td>
                    <td>26</td>
                    <td>Aktif</td>
                </tr>
                <tr>
                    <td>Andi2</td>
                    <td>Jakarta</td>
                    <td>Web Designer</td>
                    <td>21</td>
                    <td>Aktif</td>
                </tr>
                <tr>
                    <td>Andi3</td>
                    <td>Jakarta g</td>
                    <td>Web Designer</td>
                    <td>21</td>
                    <td>Aktif</td>
                </tr>
                <tr>
                    <td>Andi4</td>
                    <td>Jakarta f</td>
                    <td>Web Designer</td>
                    <td>21</td>
                    <td>Aktif</td>
                </tr>
                <tr>
                    <td>Andi5</td>
                    <td>Jakarta e</td>
                    <td>Web Designer</td>
                    <td>21</td>
                    <td>Aktif</td>
                </tr>
                <tr>
                    <td>Andi6</td>
                    <td>Jakarta d</td>
                    <td>Web Designer</td>
                    <td>21</td>
                    <td>Aktif</td>
                </tr>
                <tr>
                    <td>Andi7</td>
                    <td>Jakarta c</td>
                    <td>Web Designer</td>
                    <td>21</td>
                    <td>Aktif</td>
                </tr>
                <tr>
                    <td>Andi8</td>
                    <td>Jakarta b</td>
                    <td>Web Designer</td>
                    <td>21</td>
                    <td>Aktif</td>
                </tr>
                <tr>
                    <td>Andi9</td>
                    <td>Jakarta a</td>
                    <td>Web Designer</td>
                    <td>21</td>
                    <td>Aktif</td>
                </tr>
                <tr>
                    <td>Andi a</td>
                    <td>Jakarta 9</td>
                    <td>Web Designer</td>
                    <td>21</td>
                    <td>Aktif</td>
                </tr>
                <tr>
                    <td>Andi b</td>
                    <td>Jakarta 8</td>
                    <td>Web Designer</td>
                    <td>21</td>
                    <td>Aktif</td>
                </tr>
                <tr>
                    <td>Andi c</td>
                    <td>Jakarta 7</td>
                    <td>Web Designer</td>
                    <td>21</td>
                    <td>Aktif</td>
                </tr>
                <tr>
                    <td>Andi d</td>
                    <td>Jakarta 6</td>
                    <td>Web Designer</td>
                    <td>21</td>
                    <td>Aktif</td>
                </tr>
                <tr>
                    <td>Andi e</td>
                    <td>Jakarta 5</td>
                    <td>Web Designer</td>
                    <td>21</td>
                    <td>Aktif</td>
                </tr>
                <tr>
                    <td>Andi f</td>
                    <td>Jakarta 4</td>
                    <td>Web Designer</td>
                    <td>21</td>
                    <td>Aktif</td>
                </tr>
                <tr>
                    <td>Andi g</td>
                    <td>Jakarta 3</td>
                    <td>Web Designer</td>
                    <td>21</td>
                    <td>Aktif</td>
                </tr>
                <tr>
                    <td>Andi h</td>
                    <td>Jakarta 2</td>
                    <td>Web Designer</td>
                    <td>21</td>
                    <td>Aktif</td>
                </tr>
                <tr>
                    <td>Andi i</td>
                    <td>Jakarta 1</td>
                    <td>Web Designer</td>
                    <td>21</td>
                    <td>Aktif</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
<script type="text/javascript">
    $(document).ready(function () {
        $('.data').DataTable({
            dom: "bfrltip",
            pageLength: 2,
            lengthMenu: [2, 4, 5, 10]
        });
    });
</script>

</html>