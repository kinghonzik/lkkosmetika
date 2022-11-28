export default function () {
    class Utils {
        createStrId(str) {
            const removeDiacritics = str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            const trimmedString = removeDiacritics?.trim();
            return trimmedString?.replaceAll(' ','-')
        }

        blobToBase64(blob) {
            return new Promise((resolve, _) => {
              const reader = new FileReader();
              reader.onloadend = () => resolve(reader.result);
              reader.readAsDataURL(blob);
            });
          }
    }

    const utils = new Utils();
    return useState('utils', () => utils)
}