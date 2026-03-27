/**
 * Título de respostas JSON (compatível com title e tittle legados).
 */
window.jsonResponseTitle = function (r) {
	if (!r) {
		return '';
	}
	if (r.title != null && r.title !== '') {
		return r.title;
	}
	if (r.tittle != null && r.tittle !== '') {
		return r.tittle;
	}
	return '';
};
