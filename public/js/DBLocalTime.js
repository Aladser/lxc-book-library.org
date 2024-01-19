class DBLocalTime {
    /** получить текущее время, форматированное как время MySQL*/
    static get() {
        let formatNumber = (number) => number < 10 ? '0'+number : number;

        let date = new Date();
        let year = date.getFullYear();
        let month = formatNumber(date.getMonth());
        let day = formatNumber(date.getDay());
        let hours = formatNumber(date.getHours());
        let minutes = formatNumber(date.getMinutes());
        let seconds = formatNumber(date.getSeconds());

        return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
    }
}