<style>
    #globalRightOffcanvas {
        right: -648px;
        width: min(648px, 90vw);
        height: 100vh;
        min-height: 100vh;
        overflow: hidden;
        visibility: hidden;
        pointer-events: none;
        box-shadow: rgba(50, 50, 93, 0.16) 0 0 40px;
        transition: right 0.4s ease, visibility 0s linear 0.4s;
    }

    #globalRightOffcanvas.active {
        right: 0;
        visibility: visible;
        pointer-events: auto;
        transition-delay: 0s;
    }

    #globalRightOffcanvasOverlay {
        z-index: 1040;
        background: rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(1px);
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.3s ease, visibility 0s linear 0.3s;
    }

    #globalRightOffcanvasOverlay.active {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
        transition-delay: 0s;
    }

    #globalRightOffcanvas .global-right-offcanvas-body {
        width: 100%;
        padding-bottom: 120px;
        overflow-y: auto;
    }

    @media (max-width: 575px) {
        #globalRightOffcanvas {
            right: -90vw;
            width: 90vw;
        }

        #globalRightOffcanvas.active {
            right: 0;
        }
    }
</style>
