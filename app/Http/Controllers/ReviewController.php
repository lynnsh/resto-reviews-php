<?php

namespace App\Http\Controllers;
use App\Resto;
use App\Review;

use Illuminate\Http\Request;

class ReviewController extends Controller {
    
    public function add(Resto $resto) {
        $this->authorize('update', $resto);
        return view('review.add', ['resto' => $resto]);
    }
    
    public function add_review(Request $request) {
        $this -> validate($request, [
            'title' => 'required|max:50', 'content' => 'required|max:255',
            'rating' => array('required','regex:/^[1-5]$/')]);
        $id = $request -> resto_id;
        Resto::find($id) -> reviews() -> create([
                'user_id' => $request -> user() -> id,
                'title' => $request -> title, 
                'content' => $request -> content,
                'rating' => $request -> rating,            
            ]);         
        return redirect("resto/view/$id");
    }
    
    public function edit(Review $review) {
        $this->authorize('edit', $review);
        return view('review.edit', ['review' => $review]);
    }
    
    public function edit_review(Request $request) {
        $this -> validate($request, [
            'content' => 'required|max:255',
            'rating' => array('required','regex:/^[1-5]$/')]);
        $review = Review::find($request -> review_id);
        $review -> content = $request -> content;
        $review -> rating = $request -> rating;
        $review -> save();
        return redirect('/resto/view/'.$review->resto_id);
    }
    
    
    
}
