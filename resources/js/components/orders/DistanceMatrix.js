import { MapElementFactory } from "vue2-google-maps";

export default MapElementFactory({
  name: "distanceMatrix",

  ctr() {
    return window.google.maps.DistanceMatrixService;
  },

  events: [],

  mappedProps: {},

  props: {
    origins: { type: [Object, Array] },
    destinations: { type: [Object, Array] },
    travelMode: { type: String },
    waypoints: { type: Array}
  },

  afterCreate(directionsRenderer) {
    let DistanceMatrixService = new window.google.maps.DistanceMatrixService();

    this.$watch(
      () => [this.origins, this.destinations, this.travelMode],
      () => {
        let { origins, destinations, travelMode,} = this;
        if (!origins || !destinations || !travelMode) return;
        DistanceMatrixService.getDistanceMatrix(
          {
            origins:[origins],
            destinations:destinations,
            travelMode,
            unitSystem: window.google.maps.UnitSystem.metric,
          },
          (response, status) => {
            if (status !== "OK") return;
            // eslint-disable-next-line no-debugger
            //debugger
            console.log(response)
            var origins = response.originAddresses;
            var destinations = response.destinationAddresses;
            var resultat = response.rows[0].elements[0].distance.text;
            this.$emit('distanceSet', resultat)
            
          }
        );
      }
    );
  },
});