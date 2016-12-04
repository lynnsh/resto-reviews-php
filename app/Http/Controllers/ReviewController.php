<?php

namespace App\Http\Controllers;
use App\Resto;
use App\Review;
use Illuminate\Http\Request;

/**
 * The Controller class that handles web requests for restaurant reviews,
 * such as adding and editing a review.
 * 
 * @author Alena Shulzhenko
 * @version 2016-01-03
 */
class ReviewController extends Controller {
    
    /**
     * Returns a view with the form for adding a new review 
     * for the selected resto.
     * @param Resto $resto the resto for which new review is added.
     * @return a view with the form for adding a new review.
     */
    public function add(Resto $resto) {
        $this->authorize('update', $resto);
        return view('review.add', ['resto' => $resto]);
    }
    
   /**
     * Saves the new review to the database if the entered information is valid.
     * Any authenticated user can post a review.
     * @param Request $request the request containing the review information.
     * @return redirects to the view page for the resto this newly created
     *         review belongs to.
     */
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
    
    /**
     * Returns a view that displays a form for editing the selected review.
     * @return a view that displays a form for editing the selected review.
     */
    public function edit(Review $review) {
        $this->authorize('edit', $review);
        return view('review.edit', ['review' => $review]);
    }
    
    /**
     * Saves the edited review to the database if the entered information is valid.
     * Only the user that created a review can edit it.
     * @param Request $request the request containing the review information.
     * @return redirects to the view page for the resto this edited
     *         review belongs to when the database is successfully updated.
     */
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
