@extends('layout.main')

@section('main')
<div class="row page-titles">
    <div class="col-md-3 align-self-center">
        <h4 class="text-themecolor">Trạng thái</h4>
        <select name="" id="type_student" class="form-control chosen">
            <option value="present" selected>Đã điểm danh</option>
            <option value="absent">Chưa điểm danh</option>
        </select>
    </div>
    <div class="col-md-3 align-self-center">
        <h4 class="text-themecolor">Lớp</h4>
        <select name="" id="class_student" class="form-control chosen">
            <option value="all" selected>Toàn trường</option>
            @foreach($class as $v_class)
            <option value="{{$v_class->value}}">{{$v_class->value}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 align-self-center">
        <h4 class="text-themecolor">Ngày tháng</h4>
        <input type="date" class="form-control datepicker" id="timerange" data-date-format="DD-MM-YYYY" >
    </div>
    <div class="col-md-1 align-self-center" style="margin-top: 17px;">
        <div class="d-flex align-items-center">
            <button class="btn btn-success" id="query"><i class="fa fa-search" aria-hidden="true"></i></button>
        </div>
    </div>
    <div class="col-md-2 align-self-center">
        <div class="d-flex align-items-center" style="margin-top: 17px;">
            <button class="btn btn-success" id="excel"><i class=" far fa-file-excel" aria-hidden="true"></i> Xuất excel</button>
        </div>
    </div>
</div>

<div class="row">
    <!-- Column -->
    <div class="col-lg-4 col-md-6" id="parent_present">
        <div class="card">
            <div class="card-body">
               
                    <div class="d-flex flex-row">
                        <div class="round align-self-center round-success"><i class="ti-user"></i></div>
                        <div class="m-l-10 align-self-center">
                        <h3 class="m-b-0" id="present">{{$present}}</h3>
                            <h5 class="text-muted m-b-0">Học sinh điểm danh hôm nay</h5></div>
                    </div>

            </div>
        </div>
    </div>
    <!-- Column -->
    <!-- Column -->
    <div class="col-lg-4 col-md-6" id="parent_absent">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="round align-self-center round-info"><i class="fas fa-user-times"></i></div>
                    <div class="m-l-10 align-self-center">
                        <h3 class="m-b-0" id="absent">{{$absent}}</h3>
                        <h5 class="text-muted m-b-0">Học sinh chưa điểm danh</h5></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Column -->
    <!-- Column -->
    <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="round align-self-center round-danger"><i class="far fa-clock"></i></div>
                    <div class="m-l-10 align-self-center">
                        <h3 class="m-b-0" id="now"></h3>
                        <h5 class="text-muted m-b-0">Thời gian hiện tại</h5></div>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Báo cáo học sinh</h4>
                <button class="btn btn-primary" id="zalo"><i class=" far fa-paper-plane"></i></button>
                <div class="table-responsive w-auto">
                    <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Ảnh</th>   
                                <th>Họ và tên</th>
                                <th>Lớp</th>
                                <th>Mã học sinh</th>
                                <th>Điểm danh</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="loadtr">
                                <td style="display:none"></td>
                                <td style="display:none"></td>
                                <td style="display:none"></td>
                                <td style="display:none"></td>
                                <td style="display:none"></td>
                            </tr>
                         
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="overplay">
    <div class="messagebox_overlay" style="box-sizing: border-box; display: flex; flex-flow: column nowrap; align-items: center; position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; justify-content: flex-start;">
        <div class="messagebox_spacer" style="box-sizing: border-box; flex: 0 1 auto; height: 25%; margin-top: 0px;"></div>
        <div class="messagebox" style="box-sizing: border-box; flex: 0 1 auto; display: flex; flex-flow: column nowrap; overflow: hidden; width: auto; max-height: 62.5%;">
            <div class="loader">
                <div class="loader__figure"></div>
                <p class="loader__label">Loading</p>
            </div>                                                         
        </div>
    </div>
</div>


@endsection

@section('script')
    @include('layout.script')
<script>                                  
    (function(){
        $('#sync_st').on('click',function(e){
            e.preventDefault();
            $.ajax({
                url: '{{route('sync')}}',
                method: 'get',
            }).done(result => {
               console.log(result);
                if(result.result == true){
                    $.MessageBox({
                        buttonDone: "OK",
                        buttonFail : undefined,
                        top: "25%",
                        input: false,
                        message: "Đồng bộ thành công",
                        queue: true,
                        speed: 200,
                    });
                }else{
                    $.MessageBox({
                        buttonDone: "OK",
                        buttonFail : undefined,
                        top: "25%",
                        input: false,
                        message: "Lỗi hệ thống",
                        queue: true,
                        speed: 200,
                    });
                }
            });
        });
        $('#zalo').on('click',function(){
            $.ajax({
                url: '{{route('zalo')}}',
                method: 'post',
                headers:{
                    'X-CSRF-TOKEN': '{{csrf_token()}}' 
                },
                data:{
                    'class': $('#class_student').val(),
                }
            }).done(result => {
                console.log(result);
                if(result.result == true){
                    $.MessageBox({
                        buttonDone: "OK",
                        buttonFail : undefined,
                        top: "25%",
                        input: false,
                        message: "Thành công",
                        queue: true,
                        speed: 200,
                    });
                }else{
                    $.MessageBox({
                        buttonDone: "OK",
                        buttonFail : undefined,
                        top: "25%",
                        input: false,
                        message: "Lỗi hệ thống",
                        queue: true,
                        speed: 200,
                    });
                }
                
            });
        });

        $('#excel').on('click',function(e){
            e.preventDefault();
            var type_student = $('#type_student').val();
            var class_student = $('#class_student').val();
            var timerange = $('#timerange').val();
            $('#overplay').show();
            ajax_download('{{route('excel')}}',{
                    'type': type_student,
                    'class':class_student,
                    'time':timerange
                });

            // $.ajax({
            //     url: '{{route('excel')}}',
            //     method: 'post',
            //     headers:{
            //         'X-CSRF-TOKEN': '{{csrf_token()}}' 
            //     },
            //     data:{
            //         'type': type_student,
            //         'class':class_student,
            //         'time':timerange
            //     }
            // }).done(result => {
            //     console.log(result);
            // });

        });
        //$('#timerange').datepicker({ dateFormat: 'dd/mm/yyyy' });
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();
        var hour = today.getHours();
        var mi = today.getMinutes();
        var se = today.getSeconds();
        // if(dd<10){
        //     dd='0'+dd
        // } 
        if(mm<10){
            mm='0'+mm
        } 
        today = yyyy+'-'+mm+'-'+dd;

        $('#zalo').hide();
        $('#timerange').on('change',function(){
            if($('#timerange').val() == today){
                if($('#class_student').val() != 'all'){
                    $('#zalo').show();
                    $('#zalo').html('<i class=" far fa-paper-plane"></i> Gửi thông tin điểm danh lớp '+$('#class_student').val());
                }else{
                    $('#zalo').hide();
                }
            }else{
                $('#zalo').hide();
            }
        });
        //var sst = $('#class_student').val();
        $('#class_student').on('change', function(){
            if($('#class_student').val() != 'all'){
                if($('#timerange').val() == today){
                    $('#zalo').show();
                    $('#zalo').html('<i class=" far fa-paper-plane"></i> Gửi thông tin điểm danh lớp '+$('#class_student').val());
                }else{
                    $('#zalo').hide();
                }
            }else{
                $('#zalo').hide();
            }
        });
        $('#timerange').attr('max',today);
        $('#timerange').val(today);
        $('#now').text(today);
        $('#query').on('click', function(e){
            $('#overplay').show();
            load.clear().draw();
            e.preventDefault();
            var type_student = $('#type_student').val();
            var class_student = $('#class_student').val();
            var timerange = $('#timerange').val();
            console.log(type_student,class_student);
            $.ajax({
                url: '{{route('query_data')}}',
                method: 'post',
                headers:{
                    'X-CSRF-TOKEN': '{{csrf_token()}}' 
                },
                data:{
                    'type': type_student,
                    'class':class_student,
                    'time':timerange
                }
            }).done(result => {
                if($('#type_student').val() == 'present'){
                    $('#parent_present').show();
                    $('#present').text(result.count);
                    $('#parent_absent').hide();
                }else{
                    $('#parent_absent').show();
                    $('#absent').text(result.count);
                    $('#parent_present').hide();
                }
               
                $('#overplay').hide();
                console.log(result);
                result = result.students;
                load.clear().draw();
                result.forEach(item => {
                    if(item.time_range != null){
                        load.row.add([`<img src="${item.avatar_base64}" width="53" class="img-responsive avatar" >`,
                            item.name,
                            item.classroom,
                            item.subject_id,
                            item.time_range.checkin,

                        ]).draw();
                    }else{
                        load.row.add([`<img src="${item.avatar_base64}" width="53" class="img-responsive avatar" >`,
                            item.name,
                            item.classroom,
                            item.subject_id,
                            'Vắng',
                        ]).draw();
                    } 
                });
            });
        });
        $(".chosen").chosen();
        $('#myTable').DataTable();
        var table = $('#example').DataTable({
            "columnDefs": [{
                "visible": false,
                "targets": 2
            }],
            "order": [
                [2, 'asc']
            ],
            "displayLength": 25,
                "drawCallback": function (settings) {
                    var api = this.api();
                    var rows = api.rows({
                        page: 'current'
                    }).nodes();
                    var last = null;
                    api.column(2, {
                    page: 'current'
                }).data().each(function (group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                        last = group;
                    }
                });
            }
        });
        // Order by the grouping
        $('#example tbody').on('click', 'tr.group', function () {
            var currentOrder = table.order()[0];
            if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                table.order([2, 'desc']).draw();
            } else {
                table.order([2, 'asc']).draw();
            }
        });
        // responsive table
        $('#config-table').DataTable({
            responsive: true
        });
        var load =  $('#example23').DataTable({
            dom: 'Bfrtip',
        });
        $('.dt-buttons').hide();
        $.ajax({
            url: '{{route('load_data')}}',
            method: 'get',
        }).done(result => {
            $('#overplay').hide();
            load.clear().draw();
            console.log(result);

            result.students.forEach(item => {
                if(item.time_range != null){
                    load.row.add([`<img src="${item.avatar_base64}" width="53" class="img-responsive avatar" >`,
                        item.name,
                        item.classroom,
                        item.subject_id,
                        item.time_range.checkin,
                    ]).draw();
                }else{
                    load.row.add([`<img src="${item.avatar_base64}" width="53" class="img-responsive avatar" >`,
                        item.name,
                        item.classroom,
                        item.subject_id,
                        'Vắng',

                    ]).draw();
                } 
                
            });
        });
        function ajax_download(url, data) {
            var $iframe,
                iframe_doc,
                iframe_html;

            if (($iframe = $('#download_iframe')).length === 0) {
                $iframe = $("<iframe id='download_iframe'" +
                            " style='display: none' src='about:blank'></iframe>"
                        ).appendTo("body");
            }

            iframe_doc = $iframe[0].contentWindow || $iframe[0].contentDocument;
            if (iframe_doc.document) {
                iframe_doc = iframe_doc.document;
            }

            iframe_html = "<html><head></head><body><form method='POST' action='" +
                        url +"'>" 

            Object.keys(data).forEach(function(key){
                iframe_html += "<input type='hidden' name='"+key+"' value='"+data[key]+"'>";

            });
            iframe_html += `{!! csrf_field() !!}`;
                iframe_html +="</form></body></html>";

            iframe_doc.open();
            iframe_doc.write(iframe_html);
            $(iframe_doc).find('form').submit();
            $('#overplay').hide();
        }
    })();
</script>

@endsection