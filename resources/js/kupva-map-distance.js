export const hasMapCoordinates = (location) => {
    if (location.latitude === null || location.longitude === null || location.latitude === '' || location.longitude === '') {
        return false;
    }

    const latitude = Number(location.latitude);
    const longitude = Number(location.longitude);

    return Number.isFinite(latitude) && Number.isFinite(longitude)
        && latitude >= -11 && latitude <= -8 && longitude >= 115 && longitude <= 120;
};

export const distanceInKilometers = (origin, destination) => {
    const radians = (degrees) => degrees * Math.PI / 180;
    const latitudeDifference = radians(destination.latitude - origin.latitude);
    const longitudeDifference = radians(destination.longitude - origin.longitude);
    const haversine = Math.sin(latitudeDifference / 2) ** 2
        + Math.cos(radians(origin.latitude)) * Math.cos(radians(destination.latitude))
        * Math.sin(longitudeDifference / 2) ** 2;

    return 6371 * 2 * Math.atan2(Math.sqrt(haversine), Math.sqrt(1 - Math.min(1, haversine)));
};

export const rankNearestKupvas = (origin, kupvas) => kupvas
    .filter(hasMapCoordinates)
    .map((kupva) => ({ ...kupva, distance: distanceInKilometers(origin, kupva) }))
    .sort((first, second) => first.distance - second.distance || first.id - second.id);

export const formatDistance = (kilometers) => kilometers < 1
    ? `${Math.round(kilometers * 1000)} m`
    : `${new Intl.NumberFormat('id-ID', { maximumFractionDigits: 1 }).format(kilometers)} km`;
