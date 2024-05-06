var page =
	parseInt(new URLSearchParams(window.location.search).get("page")) || 1;

var nameOrNumberSearch = $("#nameOrNumberSearch").val();
var searchStatus = $("#searchStatus option:selected").text();
var searchEstado = $("#searchEstado option:selected").val();
var searchDate = $("#searchDate").val();

// Check if nameOrNumberSearch exists in the URL
var urlParams = new URLSearchParams(window.location.search);
if (urlParams.has("nameOrNumberSearch")) {
	nameOrNumberSearch = urlParams.get("nameOrNumberSearch");
	$("#nameOrNumberSearch").val(nameOrNumberSearch);
}

if (urlParams.has("searchStatus")) {
	searchStatus = urlParams.get("searchStatus");
	$("#searchStatus").val(searchStatus);
}

if (urlParams.has("searchEstado")) {
	searchEstado = urlParams.get("searchEstado");
	$("#searchEstado").val(searchEstado);
}

if (urlParams.has("searchDate")) {
	searchDate = urlParams.get("searchDate");
	$("#searchDate").val(searchDate);
}

$(document).ready(function () {
	carregarCards();

	$("#nameOrNumberSearch").keyup(
		debounce(function () {
			page = 1;
			nameOrNumberSearch = $("#nameOrNumberSearch").val();
			carregarCards();			
		}, 650)
	);
	$("#searchStatus").change(function () {
		page = 1;
		searchStatus = $("#searchStatus option:selected").text();
		carregarCards();
	});
	$("#searchEstado").change(function () {
		page = 1;
		searchEstado = $("#searchEstado option:selected").val();
		carregarCards();
	});
	$("#searchDate").change(function () {
		page = 1;
		searchDate = $("#searchDate").val();
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

function obterCorPorStatus($status) {
	switch ($status) {
		case "Prestação de Contas Concluída":
		case "Prestação de Contas Aprovada":
		case "Proposta/Plano de Trabalho Aprovado":
		case "Em execução":
			return "#14B240"; // verde para status positivos (bright green)
		case "Prestação de Contas em Análise":
		case "Prestação de Contas em Complementação":
		case "Aguardando Prestação de Contas":
		case "Prestação de Contas Aprovada com Ressalvas":
		case "Prestação de Contas Iniciada Por Antecipação":
		case "Proposta/Plano de Trabalho Complementado Enviado para Análise":
		case "Prestação de Contas enviada para Análise":
		case "Proposta/Plano de Trabalho Complementado em Análise":
		case "Prestação de Contas Comprovada em Análise":
		case "Assinatura Pendente Registro TV Siafi":
			return "#FF8743";
		case "Cancelado":
		case "Prestação de Contas Rejeitada":
		case "Convênio Anulado":
		case "Inadimplente":
		case "Convênio Rescindido":
			return "#FF4869"; // vermelho para status negativos (bright red)
		default:
			return "#000000"; // preto para outros casos (black)
	}
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
			let url = base_url + "convenios?page=" + previousPage;
			if (nameOrNumberSearch) {
				url += "&nameOrNumberSearch=" + encodeURIComponent(nameOrNumberSearch);
			}
			if (searchStatus && !searchStatus.startsWith("Selecione")) {
				url += "&searchStatus=" + encodeURIComponent(searchStatus);
			}
			if (searchEstado && !searchEstado.startsWith("Selecione")) {
				url += "&searchEstado=" + encodeURIComponent(searchEstado);
			}
			if (searchDate) {
				url += "&searchDate=" + encodeURIComponent(searchDate);
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
		//link.href = base_url + "convenios?page=" + i;

		let url = base_url + "convenios?page=" + i;
		if (nameOrNumberSearch) {
			url += "&nameOrNumberSearch=" + encodeURIComponent(nameOrNumberSearch);
		}
		if (searchStatus && !searchStatus.startsWith("Selecione")) {
			url += "&searchStatus=" + encodeURIComponent(searchStatus);
		}
		if (searchEstado && !searchEstado.startsWith("Selecione")) {
			url += "&searchEstado=" + encodeURIComponent(searchEstado);
		}
		if (searchDate) {
			url += "&searchDate=" + encodeURIComponent(searchDate);
		}
		link.href = url;
		link.textContent = i;
		if (i === Number(currentPage)) {
			link.classList.add("active");
		}
		li.appendChild(link);
		paginationContainer.appendChild(li);
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
			let url = base_url + "convenios?page=" + nextPage;
			if (nameOrNumberSearch) {
				url += "&nameOrNumberSearch=" + encodeURIComponent(nameOrNumberSearch);
			}
			if (searchStatus && !searchStatus.startsWith("Selecione")) {
				url += "&searchStatus=" + encodeURIComponent(searchStatus);
			}
			if (searchEstado && !searchEstado.startsWith("Selecione")) {
				url += "&searchEstado=" + encodeURIComponent(searchEstado);
			}
			if (searchDate) {
				url += "&searchDate=" + encodeURIComponent(searchDate);
			}
			window.location.href = url;
		});
		nB_li.appendChild(nextButton);
		paginationContainer.appendChild(nB_li);
	}
}

function carregarCards() {
	const cardsContainer = document.getElementById("cards-container");

	// Substitua a URL da API pela URL da sua API
	const base_url = $("#base_url").val();
	var apiUrl = base_url + "convenios/getConvenios?page=" + page;

	if (nameOrNumberSearch) {
		apiUrl += "&nameOrNumberSearch=" + nameOrNumberSearch;
	}

	if (searchStatus && !searchStatus.startsWith("Selecione")) {
		apiUrl += "&searchStatus=" + searchStatus;
	}

	if (searchEstado && !searchEstado.startsWith("Selecione")) {
		apiUrl += "&searchEstado=" + searchEstado;
	}

	if (searchDate) {
		apiUrl += "&searchDate=" + searchDate;
	}

	fetch(apiUrl) // Faz uma requisição GET para a API
		.then((response) => {
			// Verifica se a requisição foi bem sucedida
			if (!response.ok) {
				throw new Error("Erro ao carregar os dados");
			}
			// Converte a resposta para JSON
			return response.json();
		})
		.then((data) => {
			// Limpa o container de cards
			cardsContainer.innerHTML = "";

			// Itera sobre os dados e cria os cards
			data.convenios.forEach((item) => {
				// Cria um elemento <div> para o card
				const card = document.createElement("div");
				card.classList.add("card", "w-100", "mb-3");

				// Adiciona o conteúdo ao card
				card.innerHTML = `
                    <div class="card-body card-convenios">
                        <h5 class="card-title mb-3 convenio-card-state">${
													item.MUNIC_PROPONENTE
												} - ${item.UF_PROPONENTE}</h5>
                        <h6 class="card-subtitle mb-2 text-muted convenio-card-title">
                            <a href="${base_url}documentos/modelo/${
					item.NR_CONVENIO
				}">${item.OBJETO_PROPOSTA}</a></h6>
                        
                        <div class="row">
                            <div class="col-md-6">                                
                                <p class="card-text convenio-card-status">
                                    Status: <font style="color:${obterCorPorStatus(
																			item.SIT_CONVENIO
																		)}">${item.SIT_CONVENIO}</font>
                                </p>
                            </div>
                            <div class="col-md-6">                                
                                <p class="convenio-card-dates" style="text-align: right">
                                    INÍCIO DA VIGÊNCIA: ${
																			item["inicio"]
																		}  FIM: ${item["fim"]}
                                </p>
                            </div>
                        </div>
                    </div>
                `;
				// Adiciona o card ao container
				cardsContainer.appendChild(card);
			});

			populateDivpaginationContainer(data.totalPages, data.currentPage);
		})
		.catch((error) => {
			console.error("Erro ao carregar os dados:", error);
		});
}
