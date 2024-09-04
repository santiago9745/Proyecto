
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Reportes</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <table>
                        <thead>
                            <tr>
                                <th>nombre</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cancha as $row)
                                <tr>
                                    <td>{{$row->nombre}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>        
        </div>
    </div>
