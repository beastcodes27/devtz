const MAX_SCREENSHOT_WIDTH = 2000;
const MAX_SCREENSHOT_HEIGHT = 2400;
const SCREENSHOT_QUALITY = 0.86;

const canvasBlob = (canvas, quality) => new Promise((resolve) => {
    canvas.toBlob(resolve, 'image/webp', quality);
});

const optimizeScreenshot = async (file) => {
    if (!file.type.startsWith('image/') || !window.createImageBitmap) return file;

    try {
        const image = await window.createImageBitmap(file);
        const scale = Math.min(
            1,
            MAX_SCREENSHOT_WIDTH / image.width,
            MAX_SCREENSHOT_HEIGHT / image.height,
        );
        const canvas = document.createElement('canvas');
        canvas.width = Math.max(1, Math.round(image.width * scale));
        canvas.height = Math.max(1, Math.round(image.height * scale));
        const context = canvas.getContext('2d', { alpha: false });

        if (!context) {
            image.close?.();
            return file;
        }

        context.fillStyle = '#ffffff';
        context.fillRect(0, 0, canvas.width, canvas.height);
        context.drawImage(image, 0, 0, canvas.width, canvas.height);
        image.close?.();

        let optimizedBlob = await canvasBlob(canvas, SCREENSHOT_QUALITY);
        if (optimizedBlob?.size > 5 * 1024 * 1024) {
            optimizedBlob = await canvasBlob(canvas, 0.72);
        }

        if (!optimizedBlob || (optimizedBlob.size >= file.size && file.size <= 5 * 1024 * 1024)) {
            return file;
        }

        const filename = `${file.name.replace(/\.[^.]+$/, '') || 'screenshot'}.webp`;

        return new File([optimizedBlob], filename, {
            type: 'image/webp',
            lastModified: file.lastModified,
        });
    } catch {
        return file;
    }
};

const formatBytes = (bytes) => {
    if (bytes < 1024) return `${bytes} B`;
    if (bytes < 1024 * 1024) return `${Math.round(bytes / 1024)} KB`;

    return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
};

document.querySelectorAll('[data-screenshot-input]').forEach((input) => {
    input.addEventListener('change', async () => {
        const selection = input.closest('label')?.querySelector('[data-screenshot-selection]');
        const originalFiles = Array.from(input.files ?? []);

        if (!selection) return;
        if (!originalFiles.length) {
            selection.textContent = 'Choose one or several images from your device';
            return;
        }

        if (!window.DataTransfer || !window.File || !window.createImageBitmap) {
            selection.textContent = `${originalFiles.length} ${originalFiles.length === 1 ? 'screenshot' : 'screenshots'} selected`;
            return;
        }

        const submitButton = input.form?.querySelector('button[type="submit"]');
        if (submitButton) submitButton.disabled = true;

        try {
            const optimizedFiles = [];
            for (const [index, file] of originalFiles.entries()) {
                selection.textContent = `Optimizing screenshot ${index + 1} of ${originalFiles.length}…`;
                optimizedFiles.push(await optimizeScreenshot(file));
            }

            const transfer = new DataTransfer();
            optimizedFiles.forEach((file) => transfer.items.add(file));
            input.files = transfer.files;

            const originalSize = originalFiles.reduce((total, file) => total + file.size, 0);
            const optimizedSize = optimizedFiles.reduce((total, file) => total + file.size, 0);
            const savedBytes = Math.max(0, originalSize - optimizedSize);
            const countLabel = `${optimizedFiles.length} ${optimizedFiles.length === 1 ? 'screenshot' : 'screenshots'} ready`;
            selection.textContent = savedBytes > 1024
                ? `${countLabel} · ${formatBytes(savedBytes)} lighter`
                : countLabel;
        } catch {
            selection.textContent = `${originalFiles.length} ${originalFiles.length === 1 ? 'screenshot' : 'screenshots'} selected`;
        } finally {
            if (submitButton) submitButton.disabled = false;
        }
    });
});
