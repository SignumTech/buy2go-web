import { MapElementFactory } from "vue2-google-maps";

export default MapElementFactory({
  name: "routesRenderer",

  ctr() {
    return window.google.maps.RoutesRenderer;
  },

  events: [],

  mappedProps: {},

  props: {
    origin: { type: [Object, Array] },
    destination: { type: [Object, Array] },
    travelMode: { type: String },
  },

  afterCreate(routesRenderer) {
    let directionsService = new window.google.maps.DirectionsService();

    this.$watch(
      () => [this.origin, this.destination, this.travelMode],
      () => {
        let { origin, destination, travelMode } = this;
        if (!origin || !destination || !travelMode) return;
        directionsService.route(
          {
            origin,
            destination,
            travelMode,
          },
          (response, status) => {
            if (status !== "OK") return;
            // eslint-disable-next-line no-debugger
            //debugger
            routesRenderer.setDirections(response);
          }
        );
      }
    );
  },
});