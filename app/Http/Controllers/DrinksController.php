<?php

namespace App\Http\Controllers;


use App\Http\Requests;
use Illuminate\Http\Request;
use App\Drinks;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use File;
use App\goodsImage;
use Mockery\CountValidator\Exception;

class DrinksController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {// проверка, в данном случае проверяет авторизован или нет
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
     // отображение списока файлов 
    public function index() {
        $drinks = Drinks::all()->toArray();
        $data['drinks'] = $drinks;
        return view('admin/tables/drinks', $data);
    }

    public function create() {
        $drinks = array();
        return view('/admin/tables/createDrinks', $drinks);
    }

    // для помещения нового объекта в базу
    public function store(Request $request, $id=null) {
        $this->validate($request, [
            'name' => 'required|max:255',
            'price' => 'required|numeric',
            'volume' => 'numeric',
            'typeDrink'   => 'required|in:alco,soft',
            'image' => 'required|image'
        ]);

        try {
            
            //добавление 
            $file = $request->file('image');
            //эта функция обрезает фото и сохраняет обрезанный вариант с оригиналом, возвращает имя файл
            $file = goodsImage::addImage($file);
            
            //добавление в бд
            Log::notice('Успех записи');
            $drink = new Drinks();
            $drink->name = $request->input('name');
            $drink->price = $request->input('price');
            $drink->volume = $request->input('volume');
            $drink->type_drinks = $request->input('typeDrink');
            $drink->img = $file;
            $drink->save();

            //возвращаем то что в бд
            $drinks = Drinks::all()->toArray();
            $data['drinks'] = $drinks;
            return view('admin/tables/drinks', $data);
        } catch(Exception $e) {
            Log::error('Ошибка записи');
            return redirect('/home');
        }
    }

    // отображения формы и функция для поулчения
    public function edit($id) {
        try {
        $drinks = Drinks::find($id);
        $data['drinks'] = $drinks;
        $data['id'] = $id;
        return view('/admin/tables/updateDrinks', $data);
        } catch (Exception $e) {
            Log::error('Ошибка ');
            return redirect()->back();
        }
    }

    // функция для редактирования получает с edit
    public function update(Request $request, $id=null) {
        $this->validate($request, [
            'name' => 'max:255',
            'price' => 'numeric',
            'volume' => 'numeric',
            'type_drinks'   => 'required|in:alco,soft',
            'img' => 'required|image'
        ]);

        try {
        if ($id==null) {
            return view('/admin/tables/drinks');
        }
        //удаление файла из папки tmp
        $el = new Drinks();
        goodsImage::delImage($el, $id);

        $drinks = Drinks::find($id);
        $post = $request->toArray();
        $post = $post['img'];
        $file =  goodsImage::addImage($post);
            $drinks->name = $request->input('name');
            $drinks->price = $request->input('price');
            $drinks->volume = $request->input('volume');
            $drinks->type_drinks = $request->input('type_drinks');
            $drinks->img = $file;
            $drinks->save();
        $allDrinks = Drinks::all()->toArray();
        $data['drinks'] = $allDrinks;
        $data['id'] = $id;
        return view('/admin/tables/drinks', $data);
        } catch (Exception $e) {
            Log::error('Ошибка ');
            return redirect()->back();
        }
    }

    // удаление файла 
    public function destroy($id=null) {
        if ($id==null) {
            $drinks = Drinks::all()->toArray();
            $data['drinks'] = $drinks;
            return view('/admin/tables/drinks', $data);
        }
        try {
        $el = new Drinks();
        goodsImage::delImage($el, $id);
        
        Drinks::destroy($id);
        $drinks = Drinks::all()->toArray();
        $data['drinks'] = $drinks;
        $data['id'] = $id;
        return view('admin/tables/drinks', $data);
        } catch (Exception $e) {
            Log::error('Ошибка записи');
            return redirect()->back();
        }
    }

}
