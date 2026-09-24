document.querySelectorAll('[data-open-dialog]').forEach((button) => {
    button.addEventListener('click', () => {
        document.getElementById(button.dataset.openDialog)?.showModal();
    });
});

document.querySelectorAll('[data-close-dialog]').forEach((button) => {
    button.addEventListener('click', () => button.closest('dialog')?.close());
});

// Reopen a dialog after a failed submit so its errors and old input stay in view.
document.querySelectorAll('dialog[data-open-on-load]').forEach((dialog) => dialog.showModal());
