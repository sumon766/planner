import '@fortawesome/fontawesome-free/css/all.min.css';
import 'choices.js/public/assets/styles/choices.css';


//Timer for tasks
document.addEventListener('livewire:init', () => {
    // Timer polling for running timers
    Livewire.on('timer-started', (event) => {
        // You can add additional logic here if needed
    });

    Livewire.on('timer-stopped', (event) => {
        // You can add additional logic here if needed
    });
});
