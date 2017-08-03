<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>AliPic Crawler</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="/plugins/magnificPopup/magnific-popup.css">
    <link rel="stylesheet" href="/css/style.css">

</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center header">
                <div class="row">
                    <div class="col-3 title">
                        <a href="/">Ali<span>P</span>ic</a><sup>beta</sup>
                        <p>Грабер картинок из отзывов товаров с Aliexpress.</p>
                    </div>
                    <div class="col-9">
                        <form class="col-12 ali-form text-center form-inline">
                            &nbsp;&nbsp;&nbsp;&nbsp;Ссылка на товар с Али:
                            <div class="form-group col-11">
                                <input type="hidden" id="old_url" value=''>
                                <input type="hidden" id="ali_page" name="page" value="1">
                                <input class="form-control col-12" type="text" id="ali_url" name="ali_url" value="">
                            </div>
                            <input class="btn btn-primary go-btn col-1" type="submit" value="Go!">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 content-wrapper">
                <div class="content gallery">
                    <div class="tip">
                        Вставляйте ссылку на товар в поле вверху и жмите Go! <br>
                        <p>Примеры ссылок: <br>
                            https://ru.aliexpress.com/item/Anadzhelia-2017-Sexy-Lotus-leaf-Bikinis-Women-Swimsuit-Brazilian-Bikini-Set-Beach-Bathing-Suit-Push-Up/32805747722.html
                            <br>
                            https://ru.aliexpress.com/item/2015-Sexy-One-Piece-Swimsuit-Bandage-For-Women-Solid-White-and-Blue-One-shoulder-Cut-Out/32286543788.html
                        </p>
                    </div>
                </div>
                <div class="pages">

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 footer">
                Найденные баги, пожелания по улучшению и другие вопросы можно присылать сюда <a href="mailto:alipicadm@gmail.com">alipicadm@gmail.com</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="/plugins/magnificPopup/jquery.magnific-popup.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"></script>
    <script>
        $(function(){
            // When submit ali form
            $('.ali-form').submit(function(event){
                event.preventDefault();

                sendForm();
            });

            // When click on paginate
            $('body').on('click', '.jLnk', function() {
                //Set new page
                $('#ali_page').val($(this).text());

                // Send ajax
                sendForm();
            });

            $('#ali_url').click(function(){
                $(this).select();
            });

            function sendForm() {
                // Clear content block and set loading animation
                $('.content').empty().prepend('<img src="/img/load.gif" class="load-img" alt="Loading. Pls wait." />');
                $('.pages').empty();

                if($('#old_url').val() != $('#ali_url').val())
                    $('#ali_page').val('1');

                $.post("/loader.php", $('.ali-form').serialize())
                    .done(function(data) {
                        // prepeare given content
                        content ='';
                        data.content.forEach(function(value){
                            content +=  '<div class="ali-img" style="background-image: url(\'' + value + '\');" data-mfp-src="' + value + '"></div>';
                        })

                        // add content
                        $('.content').empty().prepend(content);

                        // Init magnific gallery
                        $('.gallery').magnificPopup({
                            delegate: 'div',
                            type: 'image',
                            gallery: {
                                enabled: true
                            }

                        });

                        $pagesUl = '<ul class="pagin">';
                        for(i=1;i<=data.pages;i++){
                            if(i == data.currentPage)
                                $pagesUl += '<li class="pag-item"><span class="pag-link">' + i + '</span></li>';
                            else
                                $pagesUl += '<li class="pag-item"><a href="javascript:void(0);" class="pag-link jLnk">' + i + '</a></li>';

                        }
                        $pagesUl += '</ul>';
                        $('.pages').empty().prepend($pagesUl);
                        $('#old_url').val(data.oldUrl);
                        $('#ali_url').val(data.oldUrl);
                    })
                    .fail(function(data) {
                        $('.content').empty().prepend('Ooops! Some error happened.');
                    });
            }
        });
    </script>
</body>
</html>