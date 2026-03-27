<?php
class notfoundController extends controller {

	public function index() {
		header("Location: " . BASE_URL);
	}

}