<?php

namespace App\Http\Controllers;

use App\Phones;
use Illuminate\Http\Request;

use App\Http\Requests;
use Mockery\CountValidator\Exception;
use Intervention\Image\Facades\Image ;
use Illuminate\Support\Facades\File;


class PhoneController extends Controller
{
    public function index()
    {
        $data['phones'] = Phones::all()->toArray();
        return view('admin/phones/phones', $data);
    }

    /*
     * Добавление записи в DB
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'color' => 'required|string',
            'price' => 'required|numeric',
            'display' => 'required',
            'description' => 'required',
            'image' => 'required|image'
        ]);

        try {
            //добавление картинки
            $file = $request->file('image');
            //эта функция обрезает фото и сохраняет обрезанный вариант с оригиналом, возвращает имя файл
            $file = $this->addImg($file);

            $Phones = new Phones();
            $Phones->name = $request->input('name');
            $Phones->color = $request->input('color');
            $Phones->price = $request->input('price');
            $Phones->display = $request->input('display');
            $Phones->description = $request->input('description');
            $Phones->img = $file->getClientOriginalName();
            $Phones->save();
        } catch(Exception $e) {
            Log::error('Ошибка записи');
            return redirect('/admin/');
        }

        return redirect('/admin/phones');
    }

    /*
     * Удаление записи с DB
     */
    public function destroy($id = null)
    {
        try {
            $el = Phones::where('id', '=', $id)->find($id);
            $imgName = $el->img;

            $filePath = "./tmp/" .$imgName;

            if(is_file($filePath)){
                File::delete("./tmp/cut-" .$imgName);
                File::delete("./tmp/" .$imgName);
            }

            Phones::destroy($id);

            return redirect('/admin/phones');
        } catch(Exception $e) {
            Log::error('Ошибка удаления');
            return redirect()->back();
        }
    }


    //  функция обрезает фото и сохраняет обрезанный вариант с оригиналом, возвращает имя файл
    public function addImg ($file) {
        $fileName = $file->getClientOriginalName();
        $filePath = '/tmp/' .$fileName;
        if(is_file($filePath)){
            $filePath->destroy();
        }

        Image::make($file)
            ->resize(100, 100, function($constraint) {
                $constraint->aspectRatio();
            })
            ->save('./tmp/cut-' .$fileName);

        $file->move('tmp', $fileName);
        return $file;
    }
}