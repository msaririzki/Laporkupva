export const initReportSubmission = ({ form, button, controls, canSubmit, page = window }) => {
    const label = button.querySelector('[data-submit-label]');
    const spinner = button.querySelector('[data-submit-spinner]');
    let isSubmitting = false;
    let isPreparing = false;
    let previousControlStates = [];

    const updateButton = () => {
        const isBusy = isSubmitting || isPreparing;
        button.disabled = isBusy;
        button.setAttribute('aria-busy', String(isBusy));
        spinner.classList.toggle('hidden', !isBusy);
        label.textContent = isSubmitting ? 'Mengirim…' : isPreparing ? 'Menyiapkan…' : 'Kirim laporan';
    };

    form.addEventListener('submit', (event) => {
        if (event.defaultPrevented) return;

        if (isSubmitting || isPreparing || !canSubmit()) {
            event.preventDefault();
            return;
        }

        isSubmitting = true;
        previousControlStates = controls.map((control) => [control, control.disabled]);
        controls.forEach((control) => { control.disabled = true; });
        form.setAttribute('aria-busy', 'true');
        updateButton();
    });

    page.addEventListener('pageshow', () => {
        isSubmitting = false;
        previousControlStates.forEach(([control, disabled]) => { control.disabled = disabled; });
        previousControlStates = [];
        form.removeAttribute('aria-busy');
        updateButton();
    });

    return {
        setPreparing(value) {
            isPreparing = value;
            updateButton();
        },
    };
};
