@extends ('layouts.app')

@section('sidebar')
    @include('layouts.sidebar_admin')
@endsection

@section('content')
    <h1 class="col-sm-12">Телефоны</h1>
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
                    <th>Цвет</th>
                    <th>Цена</th>
                    <th>Дисплей</th>
                    <th>Описание</th>
                    <th>Фото</th>
                    <th>Время обновления </th>
                    <th>Время сознания</th>
                    <th></th>
                    <th></th>
                </tr>
                @for ($i = 0; $i < count($phones); $i++)
                    <tr>
                        @foreach ($phones[$i] as $key => $element)
                            <td>
                                @if ($key == 'img')
                                    <img src="/tmp/cut-{{$element}}" alt="images" >
                                    {{$element = null}}
                                @endif
                                {{$element}}
                            </td>
                        @endforeach
                        <td>
                            <a class="btn btn-warning"  href="{{url('/admin/updatePhones/'.$phones[$i]['id'].'')}}" title="Редактировать запись">
                                <i class="fa fa-edit"></i></a>
                        </td>
                        <td>
                            <a class="btn btn-danger" href="{{url('/admin/phones/'.$phones[$i]['id'].'')}}" title="Удалить запись">
                                <i class="fa fa-trash-o"></i></a>
                                <script>var id = function asset (id){return id;}</script>
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
                    <h4 class="modal-title text-uppercase text-center" id="myModalLabel">Добавить телофон</h4>
                </div>
                <div class="modal-body">
                    <form action="/admin/phones" method="post" enctype="multipart/form-data">
                      @if(!empty($errors->all()))
                        <table class="table table-condensed">
                            
                            @foreach ($errors->all() as $error)
                            <tr>
                                <td class="danger">{{ $error }}</td>
                            </tr>
                            @endforeach
                        </table>
                      @endif
                        <div class="form-group">
                            <label for="name">Марка Телефона</label>
                            <input id="name" name="name" type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="color">Цвет</label>
                            <select id="color" name="color" class="form-control" >
                                      <option value="black">Черный</option>
                                      <option value="red">Красный</option>
                                      <option value="yellow">Желтый</option>
                                      <option value="grey">Серый</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="price">Цена</label>
                            <input id="price" name="price" type="number" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="display">Дисплей</label>
                            <select name="display" id="display">
                                <option value="6">6</option>
                                <option value="5">5</option>
                                <option value="4">4</option>
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
