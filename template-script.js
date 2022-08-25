const $DateOptions = {
  // weekday: 'long',
  year: 'numeric',
  month: 'long',
  day: 'numeric',
};
const $TimeOptions = {
  hour: '2-digit',
  minute: '2-digit',
};

const righeTabella = (info) =>
  `<tr>
        <td>${info.Temperature.Metric.Value}</td>
        <td>${info.RealFeelTemperature.Metric.Value}</td>
        <td>${info.Wind.Direction.Value} - ${info.Wind.Speed.Metric}</td>
        <td>${info.UVIndexText}</td>
        <td>${info.RelativeHumidity}</td>
        <td>--</td>
    </tr>`;

//DA SISTEMARE
function icona(idIcona) {
  switch (idIcona) {
    case 1:
    case 2:
    case 3:
    case 4:
      return '<i class="fa-solid fa-sun"></i>';
      break;
    case 5:
      return '<i class="fa-solid fa-sun"></i><i class="fa-solid fa-smog"></i>';
    case 6:
    case 7:
    case 8:
      return '<i class="fa-solid fa-cloud"></i>';
      break;
    case 11:
      return '<i class="fa-solid fa-smog"></i>';
      break;
    case 12:
      return '<i class="fa-solid fa-cloud-showers-heavy"></i>';
      break;
    case 13:
    case 14:
      return '<i class="fa-solid fa-cloud-showers-water"></i>';
      break;
    case 15:
      return '<i class="fa-solid fa-cloud-bolt"></i>';
      break;
    case 16:
    case 17:
      return '<i class="fa-solid fa-cloud-bolt"></i><i class="fa-solid fa-bolt"></i>';
      break;
    case 19:
    case 20:
    case 21:
      return '<i class="fa-solid fa-up-down-left-right"></i>';
      break;
    case 22:
      return '<i class="fa-solid fa-snowflake"></i><i class="fa-solid fa-snowflake"></i>';
      break;
    case 23:
      return '<i class="fa-solid fa-cloud"></i><i class="fa-solid fa-snowflake"></i>';
      break;
    case 24:
      return '<i class="fa-solid fa-icicles"></i>';
      break;
    case 25:
      return '<i class="fa-solid fa-snowflake"></i>';
      break;
    case 26:
      return '<i class="fa-solid fa-cloud-meatball"></i>';
      break;
    case 29:
      return '<i class="fa-solid fa-cloud-meatball"></i><i class="fa-solid fa-cloud-showers-heavy"></i>';
      break;
    case 30:
      return '<i class="fa-solid fa-temperature-full"></i>';
      break;
    case 31:
      return '<i class="fa-solid fa-temperature-low"></i>';
      break;
    case 32:
      return '<i class="fa-solid fa-wind"></i><i class="fa-solid fa-circle-exclamation"></i>';
      break;
    case 33:
      return '<i class="fa-solid fa-moon"></i>';
      break;
    case 34:
    case 35:
    case 36:
    case 37:
    case 38:
      return '<i class="fa-solid fa-cloud-moon"></i>';
      break;
    case 39:
    case 40:
      return '<i class="fa-solid fa-cloud-moon-rain"></i>';
      break;
    case 41:
    case 42:
      return '<i class="fa-solid fa-cloud-moon"></i><i class="fa-solid fa-bolt"></i>';
      break;
    case 43:
      return '<i class="fa-solid fa-cloud-moon-rain"></i>';
      break;
    case 44:
      return '<i class="fa-solid fa-snowflake"></i><i class="fa-solid fa-snowflake"></i>';
      break;
    default:
      return "";
      break;
  }
}


const cardA = (info) =>


  `<div class="col">
        <div class="card">
            <div class="card-body">
              <h5 class="card-title">Meteo Attuale</h5>
              <div class="card-text">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><i class="fa-solid fa-clock"></i> ${(new Date(info.LocalObservationDateTime)).toLocaleTimeString("it", $TimeOptions)}</li>
                    <li class="list-group-item">${icona(info.WeatherIcon) + " " + info.WeatherText}</li>
                    <li class="list-group-item"><i class="fa-solid fa-temperature-half"></i> ${info.Temperature.Metric.Value}°C</li>
                    <li class="list-group-item"><i class="fa-solid fa-thermometer"></i> ${info.RealFeelTemperature.Metric.Value}°C <span class="small">(percepita)</span class="small"></li>
                    <li class="list-group-item"><i class="fa-regular fa-compass"></i> ${info.Wind.Speed.Metric.Value} Km/h - ${info.Wind.Direction.Localized}</li>
                    <li class="list-group-item"><i class="fa-solid fa-droplet"></i> ${info.RelativeHumidity}% <span class="small">(umidità)</span class="small"></li>
                    <li class="list-group-item"><i class="fa-solid fa-eye"></i> ${info.Visibility.Metric.Value} Km</li>
                    <li class="list-group-item"><i class="fa-solid fa-umbrella-beach"></i> ${info.UVIndexText} <span class="small">(U.V)</span class="small"></li>
                </ul>
              </div>
            </div>
        </div>
    </div>`;

const cardB = (info) =>
  `<div class="col">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Meteo</h5>
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><i class="fa-solid fa-clock"></i> ${(new Date(info.Date)).toLocaleDateString("it", $DateOptions)}</li>
            <li class="list-group-item">  ${icona(info.Day.Icon) + " " + info.Day.IconPhrase} - ${icona(info.Night.Icon) + " " + info.Night.IconPhrase}</li>
            <li class="list-group-item"><i class="fa-solid fa-temperature-half"></i>  Min: ${info.Temperature.Minimum.Value}°C - Max: ${info.Temperature.Maximum.Value}°C</li>
            <li class="list-group-item"><i class="fa-solid fa-thermometer"></i> Min: ${info.RealFeelTemperature.Minimum.Value}°C - Max: ${info.RealFeelTemperature.Maximum.Value}°C <span class="small">(percepita)</span class="small"></li>
            <li class="list-group-item"><i class="fa-regular fa-compass"></i>  ${info.Day.Wind.Speed.Value} Km/h - ${info.Day.Wind.Direction.Localized}</li>
            <li class="list-group-item"><i class="fa-solid fa-faucet-drip"></i> Giorno: ${info.Day.PrecipitationProbability}% - Notte: ${info.Night.PrecipitationProbability}%<span class="small">(precipitazioni)</span class="small"></li>
        </ul>
       </div>
    </div>
  </div>`;

const cardC = (info) =>
  `<div class="col">
      <div class="card">
            <div class="card-body">
              <h5 class="card-title">Meteo</h5>
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><i class="fa-solid fa-clock"></i>  ${(new Date(info.DateTime)).toLocaleTimeString("it", $TimeOptions)}</li>
                <li class="list-group-item">${icona(info.WeatherIcon) + " " + info.IconPhrase}</li>
                <li class="list-group-item"><i class="fa-solid fa-temperature-half"></i> ${info.Temperature.Value}°C</li>
                <li class="list-group-item"><i class="fa-solid fa-thermometer"></i> ${info.RealFeelTemperature.Value}°C <span class="small">(percepita)</span class="small"></li>
                <li class="list-group-item"><i class="fa-regular fa-compass"></i>  ${info.Wind.Speed.Value} Km/h - ${info.Wind.Direction.Localized}</li>
                <li class="list-group-item"><i class="fa-solid fa-droplet"></i> ${info.RelativeHumidity}% <span class="small">(umidità)</span class="small"></li>
                <li class="list-group-item"><i class="fa-solid fa-eye"></i> ${info.Visibility.Value} Km</li>
                <li class="list-group-item"><i class="fa-solid fa-umbrella-beach"></i> ${info.UVIndexText} <span class="small">(U.V)</span class="small"></li>
              </ul>
            </div>
        </div>
    </div>`;
