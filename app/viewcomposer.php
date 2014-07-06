<?php

View::composer("layout.master", function ($view) {
    $authLogic = App::make("AuthLogic");
    $currentUser = $authLogic->getCurrentUser();
    $view->with("user", $currentUser);
});
