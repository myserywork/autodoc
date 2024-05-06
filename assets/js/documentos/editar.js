var quill;
var documentoId;

function salvarConteudo() {
	var conteudoHtml = quill.root.innerHTML; // Obter o HTML do editor
	var nome = $("#nomeModelo").val();
	var tipo = $("#tipoConvenio option:selected").text();
	if (nome == "" || nome.length < 3) {
		swal({
			title: "Informe o nome do modelo com pelo menos 3 caracteres!",
			icon: "warning",
		}).then(function () {
			$("#nomeModelo").focus();
		});
		return;
	}
	if (tipo == "") {
		swal({
			title: "Informe o tipo do modelo!",
			icon: "warning",
		}).then(function () {
			$("#tipoConvenio").focus();
		});
		return;
	}

	$.ajax({
		type: "POST",
		url: $("#base_url").val() + "documentos/salvar_modelo", // URL do script PHP para salvar o conteúdo
		data: {
			idConvenio: convenioId,
			nome: nome,
			tipo: tipo,
			text: conteudoHtml,
			idDocumento: documentoId,
		},
		success: function (response) {
			swal({
				title: "Tudo certo!",
				text: response.msg,
				icon: "success",
			}).then(function () {
				if (response.hasOwnProperty("documentId")) {
					documentoId = response.documentId;
					window.location.href =
						$("#base_url").val() +
						"documentos/modelo/" +
						convenioId +
						"/" +
						documentoId;
				}
			});
		},
		error: function (response) {
			swal({
				title: "Opss!",
				text: JSON.parse(response.responseText).msg,
				icon: "error",
			});
		},
	});
}

function selectLocalImage() {
	const input = document.createElement("input");
	input.setAttribute("type", "file");
	input.click();
	input.onchange = () => {
		const file = input.files[0];
		if (file) {
			uploadAndResizeImage(file);
		}
	};
}

function uploadAndResizeImage(file) {
	const reader = new FileReader();
	reader.onload = (e) => {
		const img = document.createElement("img");
		img.src = e.target.result;
		img.onload = function () {
			const range = quill.getSelection();
			const resize = window.prompt(
				'Enter custom width (e.g., "300px" or "100%"):'
			);
			if (resize) {
				img.style.width = resize;
			}
			const align = window.prompt("Enter alignment (left, center, right):");
			if (align) {
				img.style.display = "block";
				img.style.marginLeft = align === "center" ? "auto" : "0";
				img.style.marginRight = align === "center" ? "auto" : "0";
				img.style.float =
					align === "left" ? "left" : align === "right" ? "right" : "none";
			}
			const formData = new FormData();
			formData.append("image", file);

			fetch($("#base_url").val() + "documentos/upload_img", {
				method: "POST",
				body: formData,
			})
				.then((response) => response.json())
				.then((result) => {
					if (result.status === "success") {
                        console.log("HTML IMG: " + new_img.outerHTML)
                        quill.insertEmbed(range.index, 'pBlot', new_img.outerHTML);
						quill.setSelection(range.index + 1);
					} else {
						console.error(result.error);
					}
				})
				.catch((error) => {
					console.error("Error uploading image:", error);
				});
		};
	};
	reader.readAsDataURL(file);
}

$(document).ready(function () {
	var Embed = Quill.import("blots/embed");
	var convenioId = $("convenioId").val();

	if (typeof documento != "undefined") {
		documentoId = documento.id;
	}

	class TemplateMarker extends Embed {
		static create(value) {
			let node = super.create(value);
			node.setAttribute(
				"class",
				"badge rounded-pill text-dark bg-" + value.colour
			);
			node.setAttribute("data-marker", value.marker);
			node.setAttribute("data-title", value.title);
			node.innerHTML = value.title;
			return node;
		}

		static value(node) {
			return {
				marker: node.getAttribute("data-marker"),
				title: node.getAttribute("data-title"),
			};
		}
	}

	TemplateMarker.blotName = "TemplateMarker";
	TemplateMarker.tagName = "span";
	Quill.register("formats/TemplateMarker", TemplateMarker);

	var InlineBlot = Quill.import("blots/block");
	class ImageBlot extends InlineBlot {
		static create(data) {
			console.log(data);
			const node = super.create(data);
			node.setAttribute("data-src", data.src);
			node.setAttribute("src", data.src);
			node.setAttribute("style", data.style);
			console.log(node);
			return node;
		}
		static value(domNode) {
			const { src, style } = domNode.dataset;
			return { src, style };
		}
	}
	ImageBlot.blotName = "imageBlot";
	ImageBlot.className = "image-blot";
	ImageBlot.tagName = "img";
	Quill.register({ "formats/imageBlot": ImageBlot });

    let BlockEmbed = Quill.import('blots/block');
    class PBlot extends BlockEmbed {
        static create(value) {
            console.log(value);
          let node = super.create(value);
          node.innerHTML = value;
          return node;
        }
        static value(node) {
          return node.innerHTML;
        }
      }
      
      PBlot.blotName = 'pBlot';
      PBlot.tagName = 'p';
      
      Quill.register({ "formats/pBlot": PBlot });

	var toolbarOptions = [
		["bold", "italic", "underline", "strike"],
		["blockquote", "code-block"],
		[
			{
				header: 1,
			},
			{
				header: 2,
			},
		],
		[
			{
				list: "ordered",
			},
			{
				list: "bullet",
			},
		],
		["link", "image", "video"],
		[
			{
				color: [],
			},
			{
				background: [],
			},
		],
		["clean"],
	];

	var options = {
		modules: {
			toolbar: toolbarOptions,
		},
		placeholder: "Digite o conteúdo de seu documento aqui...",
		theme: "snow",
	};

	quill = new Quill("#editor", options);

	if (typeof documento !== "undefined") {
        quill.disable();
        quill.root.innerHTML = documento.texto;
		//quill.clipboard.dangerouslyPasteHTML(0, documento.texto);
        quill.enable();
	}

	$(".ql-insertCustomTags").on("change", function () {
		let selectedOption = $(this).find(":selected");
		let range = quill.getSelection(true);

		quill.insertEmbed(
			range.index,
			"TemplateMarker",
			{
				colour: selectedOption.data("colour"),
				marker: selectedOption.data("marker"),
				title: selectedOption.data("title"),
			},
			Quill.sources.USER
		);

		quill.insertText(range.index + 1, " ", Quill.sources.USER);
		quill.setSelection(range.index + 2, Quill.sources.SILENT);
		$(this).val("Selecione aqui as variáveis de seu documento...");
	});

	quill.getModule("toolbar").addHandler("image", () => {
		selectLocalImage();
	});
});
