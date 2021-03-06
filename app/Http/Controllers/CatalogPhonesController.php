<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Phones;
use Mail;

class CatalogPhonesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */ 

    // вывод данных с пагинацией    
    public function index()
    {   
        $phones = Phones::paginate(6);
        $data['phones'] = $phones;
        return view('catalog/phones/catalogPhones', $data);
    }
    // фильтр данных
    public function filter(Request $request, $id=null)
    {
        $this->validate($request, [
            'color' => 'string',
            'display' => 'string',
            'min_price' => 'numeric',
            'max_price'   => 'numeric'
        ]);
        $filterValue = array();
        $filterValue['color'] = $request->input('color');
        $filterValue['display']  = $request->input('display');
        $filterValue['min_price']  = $request->input('min_price');
        $filterValue['max_price']  = $request->input('max_price');

         if ($filterValue['max_price'] != '') {
            $phones = Phones::where('price', '<=', $filterValue['max_price'])
                                ->where('price', '>=', $filterValue['min_price'])
                                ->simplePaginate(6);
        } else {
            $phones = Phones::where('price', '>=', $filterValue['min_price'])->simplePaginate(6);
        }
        if ($filterValue['display'] != 'all') {
         $phones = Phones::where('display', '=', $filterValue['display'])
                          ->simplePaginate(6);
        }
        if ($filterValue['color'] != 'all') {
         $phones = Phones::where('color', '=', $filterValue['color'])
                          ->simplePaginate(6);
        }

        $url = $_SERVER['REQUEST_URI'];
        $phones->setPath($url);
        $data['phones'] = $phones;
        $data['arr'] = $url;
        
        return view('catalog/phones/catalogPhones', $data);
    }

    // Оформление заказа
    public function order($id = null)
    {
      $phones = Phones::find($id);
      $phoneArr['phonesName'] = $phones->name;
      $phoneArr['phonesPrice'] = $phones->price;
      $phoneArr['phonesImg'] = $phones->img;

      $data['phones'] = $phoneArr;
      $data['id'] = $id;

      return view('/catalog/phones/order', $data);
    }

    // отправка почты
    public function sendOrder(Request $request, $id = null)
    {
      $this->validate($request, [
            'name' => 'string',
            'email' => 'required|email',
            'phone' => 'required|numeric'
        ]);
        $orderValue = array();
        $orderValue['name'] = $request->input('name');
        $orderValue['email'] = $request->input('email');
        $orderValue['phone'] = $request->input('phone');

        // лучше реализовать как метод
      Mail::send('/catalog/phones/mail', $orderValue, function($message) use ($orderValue)
        {
          $message->to($orderValue['email'], 'Джон Смит')->from('cj27111992@gmail.com')->subject('Привет!');

        });

// языки, админка для админа
      return redirect('/catalog/phones');
    }
}
