$(function () {

    // ----------- Сбор фотографий -----------
    var photos = [];

    $('#album .photo img').each(function (index) {
        var $img = $(this);

        photos.push({
            src: $img.attr('src'),
            alt: $img.attr('alt') || '',
            title: $img.attr('title') || ''
        });

        $img.attr('data-index', index);
    });

    var currentIndex = 0;


    // ----------- Открытие просмотрщика -----------
    $('#album').on('click', '.photo img', function () {
        var index = Number($(this).data('index')) || 0;
        openViewer(index);
    });


    // ----------- Функция открытия -----------
    function openViewer(index) {
        currentIndex = index;

        var $overlay = $('<div id="photo-overlay"></div>');

        var $viewer = $(`
            <div id="photo-viewer">
                <button id="photo-close" title="Закрыть">&times;</button>
                <img id="photo-big" src="" alt="">
                <div id="photo-controls">
                    <button id="photo-prev">&#9664;</button>
                    <span id="photo-counter"></span>
                    <button id="photo-next">&#9654;</button>
                </div>
                <div id="photo-caption"></div>
            </div>
        `);

        $overlay.append($viewer);
        $('body').append($overlay);

        showPhoto(currentIndex);

        // Закрытие кликом по фону
        $overlay.on('click', function (e) {
            if ($(e.target).is('#photo-overlay')) {
                closeViewer();
            }
        });

        // Закрытие крестиком
        $('#photo-close').on('click', function () {
            closeViewer();
        });

        // Листание кнопками
        $('#photo-prev').on('click', function () {
            changePhoto(-1);
        });

        $('#photo-next').on('click', function () {
            changePhoto(1);
        });

        // Листание клавиатурой
        $(document).on('keydown.photoViewer', function (e) {
            if (e.key === 'Escape') {
                closeViewer();
            }
            if (e.key === 'ArrowLeft') {
                changePhoto(-1);
            }
            if (e.key === 'ArrowRight') {
                changePhoto(1);
            }
        });
    }


    // ----------- Показ фото -----------
    function showPhoto(index) {
        var data = photos[index];
        if (!data) return;

        var $img = $('#photo-big');

        $img.stop(true, true).fadeOut(200, function () {
            $img.attr({
                src: data.src,
                alt: data.alt,
                title: data.title
            }).fadeIn(200);
        });

        $('#photo-counter').text('фото ' + (index + 1) + ' из ' + photos.length);
        $('#photo-caption').text(data.title || data.alt || '');
    }


    // ----------- Переключение фото -----------
    function changePhoto(step) {
        currentIndex += step;

        if (currentIndex < 0) currentIndex = photos.length - 1;
        if (currentIndex >= photos.length) currentIndex = 0;

        showPhoto(currentIndex);
    }


    // ----------- Закрытие -----------
    function closeViewer() {
        $('#photo-overlay').remove();
        $(document).off('keydown.photoViewer');
    }

});
