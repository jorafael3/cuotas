<?php

require 'views/header.php';

$usuario =  $_SESSION["usuario"];
?>
<div id="kt_app_content" class="app-content flex-column-fluid mt-55">
    <div class="col-12">
        <div class="card">
            <div id="SECCION_TODO">
                <div class="col-12 p-12">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed table-striped fs-6 gy-3 dataTable no-footer" id="Tabla_Pendientes">

                            <!-- <tfoot align="center">
                                <tr>
                                    <th style="font-size: 16px;" class="fw-bold fs-1"></th>
                                    <th style="font-size: 16px;" class="fw-bold fs-1"></th>
                                    <th style="font-size: 16px;" class="fw-bold fs-2"></th>
                                    <th style="font-size: 16px;" class="fw-bold fs-2"></th>
                                    <th style="font-size: 16px;" class="fw-bold fs-2"></th>
                                    <th style="font-size: 16px;" class="fw-bold fs-2"></th>
                                    <th style="font-size: 16px;" class="fw-bold fs-2"></th>
                                </tr>
                            </tfoot> -->
                        </table>

                        <table id="example" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Office</th>
                                    <th>Age</th>
                                    <th>Start date</th>
                                    <th>Salary</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Tiger Nixon</td>
                                    <td>System Architect</td>
                                    <td>Edinburgh</td>
                                    <td>61</td>
                                    <td>2011-04-25</td>
                                    <td>$320,800</td>
                                </tr>
                                <tr>
                                    <td>Garrett Winters</td>
                                    <td>Accountant</td>
                                    <td>Tokyo</td>
                                    <td>63</td>
                                    <td>2011-07-25</td>
                                    <td>$170,750</td>
                                </tr>
                                <tr>
                                    <td>Ashton Cox</td>
                                    <td>Junior Technical Author</td>
                                    <td>San Francisco</td>
                                    <td>66</td>
                                    <td>2009-01-12</td>
                                    <td>$86,000</td>
                                </tr>
                                <tr>
                                    <td>Cedric Kelly</td>
                                    <td>Senior Javascript Developer</td>
                                    <td>Edinburgh</td>
                                    <td>22</td>
                                    <td>2012-03-29</td>
                                    <td>$433,060</td>
                                </tr>
                                <tr>
                                    <td>Airi Satou</td>
                                    <td>Accountant</td>
                                    <td>Tokyo</td>
                                    <td>33</td>
                                    <td>2008-11-28</td>
                                    <td>$162,700</td>
                                </tr>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Office</th>
                                    <th>Age</th>
                                    <th>Start date</th>
                                    <th>Salary</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#example').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
</script>