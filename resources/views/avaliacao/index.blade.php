@extends('adminlte::page')

@section('title', 'Avaliação')

@section('content_header')
    <h1 class="m-0 text-dark">Listagem de Avaliações</h1>
@stop

@section('content')
    @if(session()->has('message'))
        <div class="alert alert-{{session()->get('tipoMsg') ?? 'success'}}">
            {{ session()->get('message') }}
        </div>
    @endif
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="lista" class="table table-bordered table-striped nowrap"
                           cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Matrícula</th>
                            <th>Altura</th>
                            <th>Peso</th>
                            <th>IMC</th>
                            <th>Gordura %</th>
                            <th>Data Avaliação</th>
                            <th>Ação</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                </div>
                <div class="card-footer text-center">
                    <a type="button" class="btn btn-primary" href="/avaliacao/create">
                        <i class="fa fa-plus"></i> Cadastrar Avaliação
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        var tableLista = $('#lista').DataTable({
            responsive: true,
            processing: true,
            ajax: {
                url: '/avaliacao/list',
                type: 'GET',
            },
            language: {
                "sEmptyTable": "Nenhum registro encontrado",
                "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                "sInfoPostFix": "",
                "sInfoThousands": ".",
                "sLengthMenu": "_MENU_ resultados por página",
                "sLoadingRecords": "Carregando...",
                "sProcessing": "Processando...",
                "sZeroRecords": "Nenhum registro encontrado",
                "sSearch": "Pesquisar",
                "oPaginate": {
                    "sNext": "Próximo",
                    "sPrevious": "Anterior",
                    "sFirst": "Primeiro",
                    "sLast": "Último"
                },
                "oAria": {
                    "sSortAscending": ": Ordenar colunas de forma ascendente",
                    "sSortDescending": ": Ordenar colunas de forma descendente"
                }
            },
            columns: [
                {data: 'matricula', name: 'alunos.matricula'},
                {data: 'altura', name: 'avaliacoes.altura'},
                {data: 'peso', name: 'avaliacoes.peso'},
                {data: 'imc', name: 'avaliacoes.imc'},
                {data: 'gordura', name: 'avaliacoes.gordura'},
                {data: 'data_avaliacao', name: 'avaliacoes.data_avaliacao'},
                {
                    "mRender": function (data, type, full) {
                        var buttons = "";

                        buttons += '<a href="/avaliacao/edit/' + full["id"] + '" class="btn btn-sm btn-primary" title="Editar"><i class="fa fa-edit"></i> </a>';
                        buttons += '<button class="remover btn btn-sm btn-danger" title="Remover" data-id="' + full["id"] + '" type="button"><i class="fa fa-trash"></i></button>';

                        return buttons;
                    },
                }
            ]
        });

        $(document).on('click', '.remover', function() {
            var dataId = $(this).data('id');
            var token = $("input[name='_token']").val();

            Swal.fire({
                title: 'Deseja realmente remover?',
                text: "Você não poderá reverter esta ação!",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, remover!',
                confirmButtonColor: '#3085d6',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/avaliacao/destroy/' + dataId,
                        type: 'POST',
                        data: {_method: 'delete', _token: token},
                        success: function (data) {
                            debugger;
                            tableLista.ajax.reload();
                            if (data) {
                                Swal.fire("Remoção realizada com sucesso!", "", "success");
                            } else {
                                Swal.fire("Erro ao realizar remoção!", "", "warning");
                            }
                        },
                        error: function (data) {
                            debugger;
                            Swal.fire("Erro ao realizar remoção!", "", "error");
                        }
                    });
                }
            });
        });
    </script>
@stop
