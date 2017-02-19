(function ($) {
    var General = {
        favorites: {
            addToFavoritesSelector: '.add-to-favorites',
            removeFromFavoritesSelector: '.remove-from-favorites',

            init: function () {
                var self = General.favorites;

                self.initAddToFavoriteAction();
                self.initRemoveFromFavoriteAction();
            },

            initAddToFavoriteAction: function () {
                var self = General.favorites;

                $(self.addToFavoritesSelector).on(
                    'click',
                    self.onAddToFavoriteEvent
                )
            },

            initRemoveFromFavoriteAction: function () {
                var self = General.favorites;

                $(self.removeFromFavoritesSelector).on(
                    'click',
                    self.onRemoveFromFavoriteEvent
                )
            },

            onAddToFavoriteEvent: function () {
                var self = General.favorites;
                var movieID = $(this).attr('data-movie_id');

                var hasAccountPageUrl  = ($(this).attr('href') != '#');

                if (hasAccountPageUrl) {
                    return true;
                }

                var data = {
                    'action': 'seller_of_movies_add_to_favorite',
                    'movie_id': movieID
                };

                $.post(
                    SellerOfMovies.ajaxUrl,
                    data,
                    self.onAjaxAddToFavoriteResponse
                );

                return false;
            },

            onRemoveFromFavoriteEvent: function () {
                var self = General.favorites;
                var movieID = $(this).attr('data-movie_id');

                var data = {
                    'action': 'seller_of_movies_remove_from_favorite',
                    'movie_id': movieID
                };

                $.post(
                    SellerOfMovies.ajaxUrl,
                    data,
                    self.onAjaxRemoveFromFavoriteResponse
                );

                return false;
            },

            onAjaxAddToFavoriteResponse: function (response) {
                var self = General.favorites;

                if (!response.success) {
                    alert('Error! Try later!');
                    return false;
                }

                $('[data-movie_id="' + response.data.movie_id + '"]').replaceWith(
                    response.data.button
                );

                setTimeout(function () {
                    alert(response.data.message);
                }, 100)
            },

            onAjaxRemoveFromFavoriteResponse: function (response) {
                var self = General.favorites;

                console.log(response);

                if (!response.success) {
                    alert('Error! Try later!');
                    return false;
                }

                alert(response.data.message);

                location.reload();
            },
        },



        init: function () {
            var self = General;

            self.favorites.init();
        }
    }
    
    $(document).ready(function () {
        General.init();
    })
})(jQuery)
