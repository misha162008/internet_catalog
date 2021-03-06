@extends ('layouts.app')

@section('sidebar')
    @include('layouts.sidebar_admin')
@endsection

@section('content')
    <h1 class="col-sm-12">Електротовары</h1>
    <div class="col-sm-9 col-sm-push-3">
        <a class="btn btn-success btn-block btn-lg text-uppercase" data-toggle="modal" data-target="#add"
           href="#">
            <i class="fa fa-plus-square-o "></i>
            Добавить товар</a>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <tr>
                    <th>ID</th>
                    <th>Заголовок/Название товара</th>
                    <th>Марка</th>
                    <th>Произво- дитель</th>
                    <th>Цена</th>
                    <th>Статус</th>
                    <th>Описание</th>
                    <th>Фото</th>
                    <th>Время сознания</th>
                    <th>Время обновления</th>
                    <th></th>
                    <th></th>
                </tr>
                @for ($i = 0; $i < count($electrics); $i++)
                    <tr>
                        @foreach ($electrics[$i] as $key => $element)
                            <td>
                                @if ($key == 'img')
                                    <img src="/tmp/cut-{{$element}}" alt="images" >
                                    {{$element = null}}
                                @endif
                                {{$element}}
                            </td>
                        @endforeach
                        <td>
                            <a class="btn btn-warning"
                               href="{{url('/admin/electrics/edit/'.$electrics[$i]['id'].'')}}" title="Редактировать запись">
                                <i class="fa fa-edit"></i></a>
                        </td>
                        <td>
                            <a class="btn btn-danger"
                               href="{{url('/admin/electrics/'.$electrics[$i]['id'].'')}}" title="Удалить запись">
                                <i class="fa fa-trash-o"></i></a>
                        </td>

                    </tr>
                @endfor
            </table>
        </div>

    </div>

    {{--============================ Pop Up ADD ============================--}}
    <div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title text-uppercase text-center" id="myModalLabel">Добавить електротовары</h4>
                </div>
                <div class="modal-body">
                    <form action="/admin/electrics" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="title">Заголовок/Название товара</label>
                            <input id="title" name="title" type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Марка</label>
                            <input id="name" name="name" type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="producer">Производитель</label>
                            <input id="producer" name="producer" type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="price">Цена</label>
                            <input id="price" name="price" type="number" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="status">Статус</label>
                            <select name="status" id="status">
                                <option value="нет в наличии">нет в наличии</option>
                                <option value="есть в наличии">есть в наличии</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="description">Описание</label>
                            <textarea id="description" name="description" type="text" class="form-control" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="image">Картинка товара</label>
                            <input id="image" name="image" type="file" class="form-control" required>
                        </div>

                        <input type="submit" class="btn btn-success">
                        {{csrf_field()}}
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
