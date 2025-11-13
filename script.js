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

// Ensure carousel controls appear on card hover and hide on mouseleave (global handler)
$(function() {
  $('.card-compra').each(function() {
    const card = $(this);
    card.find('.carousel-control-prev, .carousel-control-next, #img-quant').hide();
  });

  $(document).on('mouseenter', '.card-compra', function() {
    const card = $(this);
    const quant = parseInt(card.find('.carousel-inner .carousel-item').length) || 0;
    if (quant > 1) card.find('.carousel-control-prev, .carousel-control-next, #img-quant').stop(true, true).fadeIn(300);
  });

  $(document).on('mouseleave', '.card-compra', function() {
    const card = $(this);
    const quant = parseInt(card.find('.carousel-inner .carousel-item').length) || 0;
    if (quant > 1) card.find('.carousel-control-prev, .carousel-control-next, #img-quant').stop(true, true).fadeOut(300);
  });
});

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

// helper: restore caret position so that the number of digits to the right
// of the caret remains the same after formatting. prefixLen counts non-digit
// characters at the start of the value (e.g., 'R$ ' has prefixLen=3).
function restoreCaretByDigitsRight(el, formattedVal, digitsRight, prefixLen = 0) {
  for (let pos = formattedVal.length; pos >= prefixLen; pos--) {
    const rightDigits = formattedVal.slice(pos).replace(/\D/g, '').length;
    if (rightDigits === digitsRight) {
      try { el.setSelectionRange(pos, pos); } catch (e) {}
      return;
    }
  }
  try { el.setSelectionRange(formattedVal.length, formattedVal.length); } catch (e) {}
}

if (precoInput.length > 0) {
  precoInput.each(function () {

    // garante valor inicial (inteiro sem casas decimais)
    if ($(this).val().trim() === '') {
      $(this).val('0');
    }

    $(this).on('input', function () {
      const el = this;
      const $el = $(this);

      // capture caret and compute digits to the right
      const raw = $el.val();
      const selStart = typeof el.selectionStart === 'number' ? el.selectionStart : raw.length;
      const digitsRight = raw.slice(selStart).replace(/\D/g, '').length;

      // só mantém números
      let digits = raw.replace(/\D/g, '');

      // remove zeros à esquerda, mas preserva "0" sozinho
      digits = digits.replace(/^0+(?=\d)/, '');

      if (digits === '') {
        $el.val('0');
        try { el.setSelectionRange(1, 1); } catch (e) {}
        return;
      }

      // converte para número (garantia extra)
      let num = parseInt(digits, 10);
      if (isNaN(num)) num = 0;

      // formata com separador de milhares no padrão BR
      const formatted = num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');

      $el.val(formatted);

      // restore caret so typing feels natural
      restoreCaretByDigitsRight(el, formatted, digitsRight, 0);
    });

    // se perder o foco e estiver vazio, força "0"
    $(this).on('blur', function () {
      if ($(this).val().trim() === '') {
        $(this).val('0');
      }
    });
  });
}

// === Preco input with immutable R$ prefix ===
const precoInputRS = $('.preco-input-rs');
if (precoInputRS.length > 0) {
  const PREFIX = 'R$ ';
  const PREFIX_LEN = PREFIX.length;

  precoInputRS.each(function () {
    const el = this;
    const $el = $(this);

    // initialize value
    if ($el.val().trim() === '' || !$el.val().startsWith(PREFIX)) {
      $el.val(PREFIX + '0');
    } else {
      // normalize existing value
      let digits = $el.val().replace(/\D/g, '');
      let num = parseInt(digits || '0', 10);
      if (isNaN(num)) num = 0;
      const formatted = num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
      $el.val(PREFIX + formatted);
    }

    // prevent deleting the prefix, but allow full selection deletion to reset to 0
    $el.on('keydown', function (e) {
      const selStart = this.selectionStart;
      const selEnd = this.selectionEnd;

      // Backspace/Delete handling
      if (e.key === 'Backspace' || e.key === 'Delete') {
        // If selection includes numeric part (i.e., user selected everything or part after prefix),
        // interpret as intent to clear and reset to R$ 0.
        if (selStart <= PREFIX_LEN && selEnd > PREFIX_LEN) {
          e.preventDefault();
          $el.val(PREFIX + '0');
          try { this.setSelectionRange(PREFIX_LEN + 1, PREFIX_LEN + 1); } catch (err) {}
          return;
        }

        // Prevent deleting the prefix itself
        if (selStart <= PREFIX_LEN && selEnd <= PREFIX_LEN) {
          e.preventDefault();
          this.setSelectionRange(PREFIX_LEN, PREFIX_LEN);
          return;
        }
      }

      // keep caret after prefix when navigating
      if ((e.key === 'ArrowLeft' || e.key === 'Home') && selStart <= PREFIX_LEN) {
        e.preventDefault();
        this.setSelectionRange(PREFIX_LEN, PREFIX_LEN);
        return;
      }
    });

    // format on input (preserve caret position)
    $el.on('input', function () {
      const raw = $el.val();
      const selStart = typeof el.selectionStart === 'number' ? el.selectionStart : raw.length;
      const digitsRight = raw.slice(selStart).replace(/\D/g, '').length;

      // extract digits only
      let digits = raw.replace(/\D/g, '');
      if (digits === '') digits = '0';
      let num = parseInt(digits, 10);
      if (isNaN(num)) num = 0;
      const formatted = num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
      const newVal = PREFIX + formatted;
      $el.val(newVal);

      // restore caret position accounting for prefix
      restoreCaretByDigitsRight(el, newVal, digitsRight, PREFIX_LEN);
    });

    // ensure caret cannot be placed inside prefix via mouse
    $el.on('focus click mouseup', function () {
      setTimeout(() => {
        try {
          if (el.selectionStart < PREFIX_LEN) {
            el.setSelectionRange(PREFIX_LEN, PREFIX_LEN);
          }
        } catch (err) {}
      }, 0);
    });

    $el.on('blur', function () {
      if ($el.val().trim() === PREFIX) $el.val(PREFIX + '0');
    });
  });
}

// === Search suggestions / autocomplete (marcas & modelos) ===
(function () {
  const debounce = (fn, wait) => {
    let t;
    return (...args) => {
      clearTimeout(t);
      t = setTimeout(() => fn(...args), wait);
    };
  };

  // Client-side cache for fast local filtering
  const searchCache = {
    marcas: [],
    modelos: [],
    total: 0,
    fetching: false,
    lastFetched: 0
  };

  function prefetchIndexIfNeeded(force = false) {
    if (searchCache.fetching) return;
    // if we already have data and not forcing, do a lightweight check (get total)
    searchCache.fetching = true;
    $.getJSON('controladores/search_index.php')
      .done(function (data) {
        const serverTotal = (data && data.total) ? parseInt(data.total, 10) : 0;
        if (force || searchCache.marcas.length === 0 || serverTotal > (searchCache.total || 0)) {
          searchCache.marcas = data.marcas || [];
          searchCache.modelos = data.modelos || [];
          searchCache.total = serverTotal;
          searchCache.lastFetched = Date.now();
        }
      })
      .always(function () {
        searchCache.fetching = false;
      });
  }

  function localFilter(q) {
    const lcq = q.toLowerCase();
    const marcas = searchCache.marcas.filter(m => m && m.toLowerCase().indexOf(lcq) !== -1);
    const modelos = searchCache.modelos.filter(m => {
      const combined = ((m.marca || '') + ' ' + (m.modelo || '')).toLowerCase();
      return combined.indexOf(lcq) !== -1;
    });

    // sort by position then alphabetically
    marcas.sort((a, b) => {
      const ap = a.toLowerCase().indexOf(lcq);
      const bp = b.toLowerCase().indexOf(lcq);
      if (ap !== bp) return ap - bp;
      return a.localeCompare(b, undefined, { sensitivity: 'base' });
    });
    modelos.sort((x, y) => {
      const xcombined = ((x.marca || '') + ' ' + (x.modelo || '')).toLowerCase();
      const ycombined = ((y.marca || '') + ' ' + (y.modelo || '')).toLowerCase();
      const xp = xcombined.indexOf(lcq);
      const yp = ycombined.indexOf(lcq);
      if (xp !== yp) return xp - yp;
      const cmp = (x.modelo || '').localeCompare((y.modelo || ''), undefined, { sensitivity: 'base' });
      if (cmp !== 0) return cmp;
      return (x.marca || '').localeCompare((y.marca || ''), undefined, { sensitivity: 'base' });
    });

    return { marcas: marcas.slice(0, 40), modelos: modelos.slice(0, 80) };
  }

  function escapeHtml(str) {
    return String(str).replace(/[&<>"]+/g, function (s) {
      return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' })[s];
    });
  }

  function capitalizeWords(str) {
    return String(str).split(/\s+/).map(s => s.charAt(0).toUpperCase() + s.slice(1).toLowerCase()).join(' ');
  }

  function highlightMatch(text, q) {
    if (!q) return escapeHtml(capitalizeWords(text));
    const escaped = escapeHtml(text);
    // create regex from tokens to bold each occurrence
    const tokens = q.trim().split(/\s+/).filter(Boolean).map(t => t.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'));
    if (tokens.length === 0) return escapeHtml(capitalizeWords(text));
    const regex = new RegExp('(' + tokens.join('|') + ')', 'ig');
    // apply capitalization then replace (on the original-case-aware escaped string)
    const capitalized = capitalizeWords(escaped);
    return capitalized.replace(regex, '<strong>$1</strong>');
  }

  function renderSuggestions($box, data, $input, q) {
    $box.empty();
    const marcas = data.marcas || [];
    const modelos = data.modelos || [];

    if (marcas.length === 0 && modelos.length === 0) {
      $box.append('<div class="text-muted small">Nenhum resultado</div>');
      return;
    }

    if (marcas.length > 0) {
      $box.append('<div class="fw-bold small text-muted ps-1">Marcas</div>');
      marcas.forEach(function (m) {
        const display = capitalizeWords(m);
        const highlighted = highlightMatch(m, q);
        const $item = $('<a href="#" class="dropdown-item small"></a>').html(highlighted);
        $item.on('click', function (e) {
          e.preventDefault();
          // navigate to compras with q set to marca
          window.location.href = 'compras.php?q=' + encodeURIComponent(display);
        });
        $box.append($item);
      });
    }

    if (modelos.length > 0) {
      $box.append('<div class="fw-bold small text-muted ps-1 mt-1">Modelos</div>');
      modelos.forEach(function (m) {
        const rawLabel = ((m.marca ? (m.marca + ' ') : '') + (m.modelo || '')).trim();
        const highlighted = highlightMatch(rawLabel, q);
        const display = capitalizeWords(rawLabel);
        const $item = $('<a href="#" class="dropdown-item small"></a>').html(highlighted);
        $item.on('click', function (e) {
          e.preventDefault();
          // navigate to compras with q set to full label so catalog filters by it
          window.location.href = 'compras.php?q=' + encodeURIComponent(display);
        });
        $box.append($item);
      });
    }
  }

  function attachAutocomplete(selector) {
    const $els = $(selector);
    if ($els.length === 0) return;
    $els.each(function () {
      const $input = $(this);
      let $box = $input.siblings('.search-suggestions');
      if ($box.length === 0) {
        $box = $('<div class="search-suggestions dropdown-menu p-2"></div>');
        $input.after($box);
      }

      let currentIndex = -1;
      const clearActive = () => { $box.find('.dropdown-item').removeClass('active'); };
      const setActive = (idx) => {
        clearActive();
        const items = $box.find('.dropdown-item');
        if (idx >= 0 && idx < items.length) {
          $(items.get(idx)).addClass('active');
          // ensure visible
          const el = items.get(idx);
          if (el && el.scrollIntoView) el.scrollIntoView({ block: 'nearest' });
        }
      };

      const doSearch = debounce(function () {
        const q = $input.val().trim();
        if (q.length < 2) { $box.hide(); return; }

        // If we have cache data, use local filtering (fast)
        if (searchCache.marcas.length > 0 || searchCache.modelos.length > 0) {
          const data = localFilter(q);
          renderSuggestions($box, data, $input, q);
          currentIndex = -1;
          $box.show();
          return;
        }

        // otherwise, attempt network search but don't show "Erro na busca" unless cache empty
        $.getJSON('controladores/search_suggestions.php', { q: q })
          .done(function (data) {
            renderSuggestions($box, data, $input, q);
            currentIndex = -1;
            $box.show();
          })
          .fail(function () {
            // fallback: try prefetch index once
            prefetchIndexIfNeeded(true);
            // show generic no-results if cache empty, avoid persistent error message
            if ((searchCache.marcas.length === 0 && searchCache.modelos.length === 0)) {
              $box.empty().append('<div class="text-muted small">Nenhum resultado</div>').show();
            } else {
              const data = localFilter(q);
              renderSuggestions($box, data, $input, q);
              currentIndex = -1;
              $box.show();
            }
          });
      }, 120);

  // prefetch when user focuses the input so subsequent typing is instant
  $input.on('focus', function () { prefetchIndexIfNeeded(); });
  $input.on('input', function () { doSearch(); });
      $input.on('keydown', function (e) {
        const items = $box.find('.dropdown-item');
        if (e.key === 'Escape') { $box.hide(); return; }
        if (items.length === 0) return;
        if (e.key === 'ArrowDown') {
          e.preventDefault();
          currentIndex = Math.min(currentIndex + 1, items.length - 1);
          setActive(currentIndex);
        } else if (e.key === 'ArrowUp') {
          e.preventDefault();
          currentIndex = Math.max(currentIndex - 1, 0);
          setActive(currentIndex);
        } else if (e.key === 'Enter') {
          // if an item is active, trigger its click; otherwise, if single item, click it
          if (currentIndex >= 0) {
            e.preventDefault();
            const itemsArr = $box.find('.dropdown-item');
            $(itemsArr.get(currentIndex)).trigger('click');
          }
        }
      });

      $input.on('blur', function () { setTimeout(function () { $box.hide(); }, 200); });
    });
  }

  $(function () {
    attachAutocomplete('#global-search');
    attachAutocomplete('#navbar-search');
  });
})();
