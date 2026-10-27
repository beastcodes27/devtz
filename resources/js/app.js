document.querySelectorAll('[data-screenshot-input]').forEach((input) => {
    input.addEventListener('change', () => {
        const selection = input.closest('label')?.querySelector('[data-screenshot-selection]');
        const count = input.files?.length ?? 0;

        if (!selection) return;

        selection.textContent = count
            ? `${count} ${count === 1 ? 'screenshot' : 'screenshots'} selected`
            : 'Choose one or several images from your device';
    });
});
