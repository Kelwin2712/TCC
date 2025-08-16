  document.querySelectorAll('.multi-carousel').forEach(function (multipleItemCarousel) {
      if (window.matchMedia("(min-width:576px)").matches) {
          // Inicializa o carousel sem autoplay
          new bootstrap.Carousel(multipleItemCarousel, { interval: false });
          
          // Evento para os botões de controle
          $('.carousel-control-prev, .carousel-control-next').on('click', function () {
              var targetId = $(this).data('bs-target');
              var $carousel = $(targetId);
              var classMatch = $carousel.attr('class').match(/multi-carousel-(\d+)/);
              var itemsToShow = classMatch ? parseInt(classMatch[1]) : 1;
              
              var $carouselInner = $carousel.find('.carousel-inner');
              var cards = $carousel.find('.carousel-item');
              
              if (cards.length < 2) return; // não dá pra medir se só tem 1 item
              
              // Agora o cardWidth inclui gap real
              var cardWidth = cards[1].getBoundingClientRect().left - cards[0].getBoundingClientRect().left;
              var scrollPosition = $carouselInner.scrollLeft();
              var maxScroll = $carouselInner[0].scrollWidth - $carouselInner[0].clientWidth;
              
              var scrollStep = cardWidth * itemsToShow * 500;
              
              if ($(this).hasClass('carousel-control-next')) {
                  if (scrollPosition + scrollStep <= maxScroll) {
                      $carouselInner.stop().animate({ scrollLeft: scrollPosition + scrollStep }, 400);
                  } else {
                      // Rolar até o fim com segurança
                      $carouselInner.stop().animate({ scrollLeft: maxScroll }, 400);
                  }
              } else {
                  if (scrollPosition - scrollStep >= 0) {
                      $carouselInner.stop().animate({ scrollLeft: scrollPosition - scrollStep }, 400);
                  } else {
                      // Voltar para o início com segurança
                      $carouselInner.stop().animate({ scrollLeft: 0 }, 400);
                  }
              }
          });
          
          
      } else {
          // Em telas pequenas, usa o modo padrão do Bootstrap (slide)
          $(multipleItemCarousel).addClass('slide');
      }
  });

//Tela de login e cadastro

function mosescFunc(button) {
  const input = button.closest('.form-floating').querySelector('input');
  const icon = button.querySelector('i');

  if (input.type === 'password') {
    input.type = 'text';
    icon.className = 'bi bi-eye-fill';
  } else {
    input.type = 'password';
    icon.className = 'bi bi-eye-slash';
  }
}

function showMosesc(input) {
  const container = input.closest('.form-floating');
  const button = container.querySelector('button');
  const icon = button.querySelector('i');

  if (input.value.length === 0) {
    button.disabled = true;
    button.classList.add('opacity-0');
    input.type = 'password';
    icon.className = 'bi bi-eye-slash';
  } else {
    button.disabled = false;
    button.classList.remove('opacity-0');
  }
}

//Mascara de telefone
const handlePhone = (event) => {
  let input = event.target
  input.value = phoneMask(input.value)
}

const phoneMask = (value) => {
  if (!value) return ""
  value = value.replace(/\D/g,'')
  value = value.replace(/(\d{2})(\d)/,"($1) $2")
  value = value.replace(/(\d)(\d{4})$/,"$1-$2")
  return value
}

//Mascara de cpf

function cnpj(v) {
    v=v.replace(/\D/g,"")                           //Remove tudo o que não é dígito
    v=v.replace(/^(\d{2})(\d)/,"$1.$2")             //Coloca ponto entre o segundo e o terceiro dígitos
    v=v.replace(/^(\d{2})\.(\d{3})(\d)/,"$1.$2.$3") //Coloca ponto entre o quinto e o sexto dígitos
    v=v.replace(/\.(\d{3})(\d)/,".$1/$2")           //Coloca uma barra entre o oitavo e o nono dígitos
    v=v.replace(/(\d{4})(\d)/,"$1-$2")              //Coloca um hífen depois do bloco de quatro dígitos
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
