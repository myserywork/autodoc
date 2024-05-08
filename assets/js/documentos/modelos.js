var page =
	parseInt(new URLSearchParams(window.location.search).get("page")) || 1;

var searchNome = $("#searchNome").val();
var searchTipo = $("#searchTipo option:selected").val();

// Check if nameOrNumberSearch exists in the URL
var urlParams = new URLSearchParams(window.location.search);
if (urlParams.has("searchNome")) {
	searchNome = urlParams.get("searchNome");
	$("#searchNome").val(searchNome);
}

if (urlParams.has("searchTipo")) {
	searchTipo = urlParams.get("searchTipo");
	$("#searchTipo").val(searchTipo);
}

$(document).ready(function () {
	carregarCards();

	$("#searchNome").keyup(
		debounce(function () {
			page = 1;
			searchNome = $("#searchNome").val();
			carregarCards();			
		}, 650)
	);
	$("#searchTipo").change(function () {
		page = 1;
		searchTipo = $("#searchTipo option:selected").val();
		carregarCards();
	});

});

function debounce(func, wait, immediate) {
	var timeout;
	return function () {
		var context = this,
			args = arguments;
		var later = function () {
			timeout = null;
			if (!immediate) func.apply(context, args);
		};
		var callNow = immediate && !timeout;
		clearTimeout(timeout);
		timeout = setTimeout(later, wait);
		if (callNow) func.apply(context, args);
	};
}

function populateDivpaginationContainer(totalPages, currentPage) {
	const paginationContainer = document.getElementById("pagination");
	paginationContainer.innerHTML = "";
	const base_url = $("#base_url").val();
	const cardsContainer = document.getElementById("cards-container");

	if(totalPages == 0){
		const nothingFound = document.createElement("span");
		nothingFound.classList.add("center-block");
		nothingFound.innerHTML = "Nenhum item encontrado...";
		cardsContainer.appendChild(nothingFound);
		return;
	}

	// Previous button
	if (Number(currentPage) != 1) {
		const pB_li = document.createElement("li");
		const previousButton = document.createElement("a");
		previousButton.textContent = "<<";
		previousButton.href = "#";
		previousButton.disabled = currentPage === 1;
		previousButton.addEventListener("click", () => {
			const previousPage = Number(currentPage) - 1;
			let url = base_url + "documentos/modelos?page=" + previousPage;
			if (searchNome) {
				url += "&searchNome=" + encodeURIComponent(searchNome);
			}
			if (searchTipo && !searchTipo.startsWith("Selecione")) {
				url += "&searchTipo=" + encodeURIComponent(searchTipo);
			}
			window.location.href = url;
		});
		pB_li.appendChild(previousButton);
		paginationContainer.appendChild(pB_li);
	}

    // Page links
	const maxPagesToShow = 3;
	const startPage = Math.max(1, currentPage - maxPagesToShow);
	const endPage = Math.min(
		totalPages,
		Number(currentPage) + Number(maxPagesToShow)
	);

	for (let i = startPage; i <= endPage; i++) {
		const li = document.createElement("li");
		const link = document.createElement("a");
        let url = base_url + "documentos/modelos?page=" + i;
        if (searchNome) {
            url += "&searchNome=" + encodeURIComponent(searchNome);
        }
        if (searchTipo && !searchTipo.startsWith("Selecione")) {
            url += "&searchTipo=" + encodeURIComponent(searchTipo);
        }
		link.href = url;
		link.textContent = i;
		if (i === Number(currentPage)) {
			link.classList.add("active");
		}
		li.appendChild(link);
		paginationContainer.appendChild(li);

        console.log("Entrei Aqui! 4");
	}

	// Next button
	if (Number(currentPage) != totalPages) {
		const nB_li = document.createElement("li");
		const nextButton = document.createElement("a");
		nextButton.textContent = ">>";
		nextButton.href = "#";
		nextButton.disabled = currentPage === totalPages;
		nextButton.addEventListener("click", () => {
			const nextPage = Number(currentPage) + 1;
            let url = base_url + "documentos/modelos?page=" + nextPage;
			if (searchNome) {
				url += "&searchNome=" + encodeURIComponent(searchNome);
			}
			if (searchTipo && !searchTipo.startsWith("Selecione")) {
				url += "&searchTipo=" + encodeURIComponent(searchTipo);
			}
			window.location.href = url;
		});
		nB_li.appendChild(nextButton);
		paginationContainer.appendChild(nB_li);
	}
    console.log("Entrei Aqui! 3");
}

function carregarCards() {
    const cardsContainer = document.getElementById('cards-container');
    cardsContainer.innerHTML = '';

    // Substitua a URL da API pela URL da sua API
    const base_url = $('#base_url').val();
    var apiUrl = base_url + 'documentos/getDeafaultDocumentos?page=' + page;  

    if (searchNome) {
		apiUrl += "&searchNome=" + searchNome;
	}
	if (searchTipo && !searchTipo.startsWith("Selecione")) {
		apiUrl += "&searchTipo=" + searchTipo;
	}

    fetch(apiUrl) // Faz uma requisição GET para a API
        .then(response => {
            // Verifica se a requisição foi bem sucedida
            if (!response.ok) {
                throw new Error('Erro ao carregar os dados');
            }
            // Converte a resposta para JSON
            return response.json();
        })
        .then(data => {

            console.log(data.page);
            // Itera sobre os dados e cria os cards
            data.data.forEach(item => {
                // Cria um elemento <div> para o card
                const card = document.createElement('div');
                card.classList.add('card', 'w-100', 'mb-3');

                // Adiciona o conteúdo ao card
                card.innerHTML = `
                <div class="card-body card-documentos">
                    <h5 class="card-title mb-1 convenio-card-state"><i class="fa fa-laptop" aria-hidden="true"></i> ${item.tipo}</h5>
                    <h5 class="card-title mb-3 convenio-card-state"><i class="fa fa-calendar" aria-hidden="true"></i>  ${item.criado_em}</h5>
					<h6 class="card-subtitle mb-2 text-muted convenio-card-title">
                        <a href="${base_url}documentos/modelo/${item.id_convenio}/${item.id}">${item.nome}</a>
                    </h6>
                </div>
            `;
                // Adiciona o card ao container
                cardsContainer.appendChild(card);
            });
            populateDivpaginationContainer(data.totalPages, data.page);
        })
        .catch(error => {
            console.error('Erro ao carregar os dados:', error);
        });
}
