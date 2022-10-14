<template>
    <div class="row">
      <h2>Routes Google Maps</h2>
      <div>
        <table>
          <tr>
            <th>Start Location</th>
            <th><GmapAutocomplete @place_changed="setPlace" /></th>
            <th style="width: 50%;"><button class="btn" @click="addMarker()">Add</button></th>
          </tr>
          <tr>
            <th>End Location</th>
            <th><GmapAutocomplete @place_changed="setPlace" /></th>
            <th style="width: 50%;"><button class="btn" @click="addMarker()">Add</button></th>
          </tr>
        </table>
      </div>
      <br />
      <GmapMap :zoom="7" :center="center" style="width: 100%; height: 400px">
        <DirectionsRenderer
          travelMode="DRIVING"
          :origin="startLocation"
          :destination="endLocation"
        />
        <DirectionsRenderer
          travelMode="DRIVING"
          :origin="startLocation"
          :destination="endLocation2"
        />
      </GmapMap>
    </div>
  </template>
  
  <script>
  import DirectionsRenderer from "./DirectionsRenderer";
  
  export default {
    components: {
      DirectionsRenderer,
    },
    data() {
      return {
        center: {lat:8.9954,lng:38.8276},
        currentPlace: null,
        markers: [],
        places: [],
        startLocation: {lat:8.9954,lng:38.8276},
        endLocation: {lat:9.0332,lng:38.7497},
        endLocation2: {lat:9.1332,lng:38.8},
      };
    },
    methods: {
      setPlace(place) {
        this.currentPlace = place;
      },
      addMarker(index) {
        this.startLocation = {lat:8.9954,lng:38.8276}
        this.endLocation = {lat:9.0332,lng:38.7497}
        this.endLocation2 = {lat:9.1332,lng:38.8}
      },
    },
  };
  </script>