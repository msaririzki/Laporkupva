import assert from 'node:assert/strict';
import { test } from 'node:test';
import { distanceInKilometers, formatDistance, hasMapCoordinates, rankNearestKupvas } from '../../resources/js/kupva-map-distance.js';

test('missing, nonnumeric, and coordinates outside the supported NTB area are not mapped', () => {
    [
        { latitude: null, longitude: 116.12 },
        { latitude: -8.58, longitude: null },
        { latitude: '', longitude: 116.12 },
        { latitude: undefined, longitude: 116.12 },
        { latitude: 'invalid', longitude: 116.12 },
        { latitude: 0, longitude: 0 },
        { latitude: -6.2, longitude: 106.8 },
    ].forEach((location) => assert.equal(hasMapCoordinates(location), false));
    assert.equal(hasMapCoordinates({ latitude: '-8.58', longitude: '116.12' }), true);
});

test('distances use kilometers on the earth instead of raw latitude differences', () => {
    assert.equal(distanceInKilometers({ latitude: -8.58, longitude: 116.12 }, { latitude: -8.58, longitude: 116.12 }), 0);
    const kilometers = distanceInKilometers({ latitude: 0, longitude: 0 }, { latitude: 0, longitude: 1 });
    assert.ok(kilometers > 111 && kilometers < 112);
});

test('nearest offices are ranked by distance and missing locations are excluded without mutating data', () => {
    const offices = [
        { id: 3, latitude: -8.62, longitude: 116.15 },
        { id: 2, latitude: -8.58, longitude: 116.12 },
        { id: 1, latitude: -8.58, longitude: 116.12 },
        { id: 4, latitude: null, longitude: null },
    ];
    const ranked = rankNearestKupvas({ latitude: -8.58, longitude: 116.12 }, offices);
    assert.deepEqual(ranked.map((office) => office.id), [1, 2, 3]);
    assert.ok(ranked[2].distance > 0);
    assert.deepEqual(offices.map((office) => office.id), [3, 2, 1, 4]);
    assert.equal(offices[0].distance, undefined);
});

test('an empty set of located offices returns no nearest result', () => {
    assert.deepEqual(rankNearestKupvas({ latitude: -8.58, longitude: 116.12 }, [{ id: 1, latitude: null, longitude: null }]), []);
});

test('distances are readable in Indonesian with meters for nearby offices', () => {
    assert.equal(formatDistance(0.35), '350 m');
    assert.equal(formatDistance(1.25), '1,3 km');
});
