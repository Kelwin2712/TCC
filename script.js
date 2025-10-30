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

// === Máscaras de entrada ===

// Telefone
const formatPhone = (value) => {
  if (!value) return "";
  return value
    .replace(/\D/g, "")                // Remove tudo que não for número
    .replace(/^(\d{2})(\d)/, "($1) $2")
    .replace(/(\d)(\d{4})$/, "$1-$2"); // Formata final: (00) 9 9999-9999
};

const handlePhone = (event) => {
  event.target.value = formatPhone(event.target.value);
};

// CNPJ
const formatCNPJ = (value) => {
  if (!value) return "";
  return value
    .replace(/\D/g, "")
    .replace(/^(\d{2})(\d)/, "$1.$2")
    .replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3")
    .replace(/\.(\d{3})(\d)/, ".$1/$2")
    .replace(/(\d{4})(\d)/, "$1-$2");
};

// CPF
const formatCPF = (value) => {
  if (!value) return "";
  return value
    .replace(/\D/g, "")
    .replace(/(\d{3})(\d)/, "$1.$2")
    .replace(/(\d{3})(\d)/, "$1.$2")
    .replace(/(\d{3})(\d{1,2})$/, "$1-$2");
};

const handleCPF = (event) => {
  event.target.value = formatCPF(event.target.value);
};

// CEP
const formatCEP = (value) => {
  if (!value) return "";
  return value
    .replace(/\D/g, "")
    .slice(0, 8)
    .replace(/^(\d{5})(\d)/, "$1-$2"); // Ex: 12345-678
};

const handleCEP = (event) => {
  event.target.value = formatCEP(event.target.value);
};


// === Aplicação automática das máscaras ===
$(document).ready(() => {
  // Aplica formatação inicial
  $(".telefone-mask").each((_, el) => el.value = formatPhone(el.value));
  $(".cpf-mask").each((_, el) => el.value = formatCPF(el.value));
  $(".cnpj-mask").each((_, el) => el.value = formatCNPJ(el.value));
  $(".cep-mask").each((_, el) => el.value = formatCEP(el.value));

  // Eventos de digitação
  $(document)
    .on("input", ".telefone-mask", handlePhone)
    .on("input", ".cpf-mask", handleCPF)
    .on("input", ".cnpj-mask", (e) => e.target.value = formatCNPJ(e.target.value))
    .on("input", ".cep-mask", handleCEP);
});


$('.sidebar-drop').on('click', function () {
  $(this).toggleClass('active');
})

const precoInput = $('.preco-input');

if (precoInput.length > 0) {
  precoInput.each(function () {

    // garante valor inicial (inteiro sem casas decimais)
    if ($(this).val().trim() === '') {
      $(this).val('0');
    }

    $(this).on('input', function () {
      // só mantém números
      let digits = $(this).val().replace(/\D/g, '');

      // remove zeros à esquerda, mas preserva "0" sozinho
      digits = digits.replace(/^0+(?=\d)/, '');

      if (digits === '') {
        $(this).val('0');
        return;
      }

      // converte para número (garantia extra)
      let num = parseInt(digits, 10);
      if (isNaN(num)) num = 0;

      // formata com separador de milhares no padrão BR
      const formatted = num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');

      $(this).val(formatted);
    });

    // se perder o foco e estiver vazio, força "0"
    $(this).on('blur', function () {
      if ($(this).val().trim() === '') {
        $(this).val('0');
      }
    });
  });
}
