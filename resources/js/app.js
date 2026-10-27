import "@fontsource/cormorant-garamond/400.css";
import "@fontsource/cormorant-garamond/500.css";
import "@fontsource/cormorant-garamond/600.css";
import "@fontsource/montserrat/400.css";
import "@fontsource/montserrat/500.css";
import "@fontsource/montserrat/600.css";
import Alpine from "alpinejs";
import videoPlayer from "./video-player";
import uploadForm from "./upload-form";
import iconSelect from "./icon-select";

window.Alpine = Alpine;

Alpine.data("iconSelect", iconSelect);
Alpine.data("uploadForm", uploadForm);
Alpine.data("videoPlayer", videoPlayer);

Alpine.start();
