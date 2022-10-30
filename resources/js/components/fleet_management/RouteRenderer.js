import { MapElementFactory } from "vue2-google-maps";

export default MapElementFactory({
  name: "routesRenderer",

  ctr() {
    return window.google.maps.DirectionsRenderer;
  },

  events: [],

  mappedProps: {},

  props: {
    routePath: { type: Array },
    travelMode: { type: String},
    map: {type: Object}
  },

  afterCreate(routesRenderer) {
    let directionsService = new window.google.maps.DirectionsService();
    
    this.$watch(
      () => [this.routePath, this.travelMode, this.map],
      () => {
        let { routePath, travelMode, map } = this;
        if (!routePath || !travelMode || !map) return;
        
        for(var i=0; i<routePath.length; i++){
            var src = routePath[i];
            var des = routePath[i+1];
            directionsService.route(
              {
                origin:src,
                destination:des,
                travelMode,
              },
              (response, status) => {
                if (status !== "OK") return;
                var path = new window.google.maps.MVCArray();
                //Set the Path Stroke Color
                var poly = new window.google.maps.Polyline({
                  map: map,
                  strokeColor: '#4986E7'
                });
                // eslint-disable-next-line no-debugger
                //debugger
                poly.setPath(path);
                for (var i = 0, len = response.routes[0].overview_path.length; i < len; i++) {
                    path.push(response.routes[0].overview_path[i]);
                  }
                  
                //routesRenderer.setDirections(response);
              }
            );
        }

      }
    );
  },
});