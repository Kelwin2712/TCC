document.querySelectorAll('.multi-carousel').forEach(function (multipleItemCarousel) {
  if (window.matchMedia("(min-width:576px)").matches) {

    new bootstrap.Carousel(multipleItemCarousel, { interval: false });


    $('.carousel-control-prev, .carousel-control-next').on('click', function () {
      var targetId = $(this).data('bs-target');
      var $carousel = $(targetId);
      var classMatch = $carousel.attr('class').match(/multi-carousel-(\d+)/);
      var itemsToShow = classMatch ? parseInt(classMatch[1]) : 1;

      var $carouselInner = $carousel.find('.carousel-inner');
      var cards = $carousel.find('.carousel-item');

      if (cards.length < 2) return;


      var cardWidth = cards[1].getBoundingClientRect().left - cards[0].getBoundingClientRect().left;
      var scrollPosition = $carouselInner.scrollLeft();
      var maxScroll = $carouselInner[0].scrollWidth - $carouselInner[0].clientWidth;

      var scrollStep = cardWidth * itemsToShow * 1;

      console.log({ cardWidth, scrollPosition, maxScroll, scrollStep, itemsToShow });

      if ($(this).hasClass('carousel-control-next')) {
        if (scrollPosition + scrollStep <= maxScroll) {
          $carouselInner.stop().animate({ scrollLeft: scrollPosition + scrollStep }, 500);
        } else {
          $carouselInner.stop().animate({ scrollLeft: maxScroll }, 500);
        }
      } else {
        if (scrollPosition - scrollStep >= 0) {
          $carouselInner.stop().animate({ scrollLeft: scrollPosition - scrollStep }, 500);
        } else {
          $carouselInner.stop().animate({ scrollLeft: 0 }, 500);
        }
      }
    });


  } else {

    $(multipleItemCarousel).addClass('slide');
  }
});

const alt = $('#alert-mensagem').parent();

if (alt.length) {
  alt.show();
  alt.delay(1500).fadeOut(1500, function () {
    alt.remove();
  });
}

$(window).on("scroll", function () {
  const header = $("header.float");
  if (header.length === 0) return;

  const scrollY = $(window).scrollTop();

  if (scrollY > header.outerHeight() && !header.hasClass("floating")) {
    header.addClass("floating");
  } else if (scrollY <= header.outerHeight() && header.hasClass("floating")) {
    header.removeClass("floating");
    $(window).scrollTop(0);
  }
});

$(".favoritar").click(function () {
  $(this).find("i").toggleClass("bi-heart bi-heart-fill");
});


$(".favoritar-danger").click(function () {
  $(this).find("i").toggleClass("text-danger");
});

function toggleSenha(button, InputID) {
  const input = document.querySelector(`#${InputID}`);
  const icon = button.querySelector('i');


  const type = input.type === 'password' ? 'text' : 'password';
  input.type = type;

  icon.classList.toggle('bi-eye-slash');
  icon.classList.toggle('bi-eye-fill');
}

function showToggleSenha(input, ButtonID) {
  const button = document.querySelector(`#${ButtonID}`);
  const icon = button.querySelector('i');

  if (input.value.length > 0) {
    button.classList.remove('opacity-0');
    button.disabled = false;
  }
  else {
    button.classList.add('opacity-0');
    button.disabled = true;
    icon.classList.remove('bi-eye-fill');
    icon.classList.add('bi-eye-slash');
  }
}

$(window).scroll(function () {
  if ($(this).scrollTop() > 100) {
    $('#top-button').fadeIn();
  } else {
    $('#top-button').fadeOut();
  }
});

$("#top-button").click(function () {
  $("html, body").animate({ scrollTop: 0 }, "slow");
  return false;
});

const handlePhone = (event) => {
  let input = event.target
  input.value = phoneMask(input.value)
}

const phoneMask = (value) => {
  if (!value) return ""
  value = value.replace(/\D/g, '')
  value = value.replace(/(\d{2})(\d)/, "($1) $2")
  value = value.replace(/(\d)(\d{4})$/, "$1-$2")
  return value
}



function cnpj(v) {
  v = v.replace(/\D/g, "")
  v = v.replace(/^(\d{2})(\d)/, "$1.$2")
  v = v.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3")
  v = v.replace(/\.(\d{3})(\d)/, ".$1/$2")
  v = v.replace(/(\d{4})(\d)/, "$1-$2")
  return v
}

const handleCPF = (event) => {
  let input = event.target
  input.value = cpfMask(input.value)
}

const cpfMask = (value) => {
  if (!value) return ""
  value = value.replace(/\D/g, "")
  value = value.replace(/(\d{3})(\d)/, "$1.$2")
  value = value.replace(/(\d{3})(\d)/, "$1.$2")
  value = value.replace(/(\d{3})(\d{1,2})$/, "$1-$2")
  return value
}


$('.sidebar-drop').on('click', function() {
  $(this).toggleClass('active');
})